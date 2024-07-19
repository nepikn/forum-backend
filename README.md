# 留言板 - 後端

- [API](#api)
- [主要技術](#主要技術)
- [安裝](#安裝)
- [學習內容](#學習內容)

## API

- 增／刪工作階段
- 新增用戶
- 修改用戶名稱
- 增／刪／查／改留言
- 查看留言數量

## 主要技術

- PHP v8
- MySQL v8

## 安裝

```bash
ssh DESTINATION
cd /var/www/html/
git clone git@github.com:nepikn/forum-backend.git

dir=api
dest=$dir/forum/
if [[ ! -d $dir ]]; then
  mkdir $dir
elif [[ "$(ls -A $dest)" ]]; then
  read -p "Remove '$dest'? (Y)" confirm
  rm $dest -fr
fi
mv forum-backend/ $dest

cd $dest
sudo ln -s $(realpath .conf) /etc/apache2/conf-available/api-forum.conf
sudo a2enconf api-forum.conf
sudo systemctl restart apache2

cd db/
read -p "Enter the password of the MySQL user 'admin': " password
sed -i "s/auth_string/$password/g" init.sql conn.php
sudo mysql -u root < init.sql
sudo systemctl restart mysql
```

## 學習內容

- [學習歷程 - MySQL](https://hackmd.io/IGSwDtGbShqUfFx2O1djTQ?view)
- [學習歷程 - PHP](https://hackmd.io/brEuH5vtReOs5fh8_X7L6A?view)
- 藉由 `AliasMatch` 將 HTTP 請求導向 `index.php`

```conf
; .conf
AliasMatch "/api/forum" "/var/www/html/api/forum/index.php"
```

- 依據 `$_SERVER['REQUEST_URI']` 判斷 `require` 的對象

```php
// index.php
preg_match('/\/([^\/]+)/', str_replace(
  getenv('API_BASE'),
  '',
  parse_url($_SERVER['REQUEST_URI'])['path']
), $matches);
require "$matches[1].php";
```

- 向 `Router` 登記 HTTP 請求方法和路徑

```php
// routes/comment.php
$router = new Router('comment', new CommentController());

$router->post('');
$router->get('');
$router->get('/length');
$router->put('/:id');
$router->delete('/:id');
```

- `Router` 以 `__call()` 處理登記

```php
// util/router.php
class Router {
  // ...

  function __call($route_method, $args) {
    // ...

    $matches = $this->getRoutePregMatch($route);
    if (!count($matches)) return;

    $this->req['args'] = array_filter(
      $matches,
      fn ($key) => is_string($key),
      ARRAY_FILTER_USE_KEY
    );

    array_push($this->handles, $handle);
    array_push($this->matched_routes, $route);
  }

  // ...
}
```

- `Controller` 以 `__invoke()` 處理 HTTP 請求

```php
// util/controller.php
class Controller {
  // ...

  function __invoke($method, $req) {
    if (
      apache_request_headers()['User-Agent'] != "HTTPie"
      && in_array($method, ['POST', 'PUT', 'DELETE'])
      && empty($_COOKIE['PHPSESSID'])
    ) {
      respond('no session id', 400);
      return;
    }

    $this->req = $req;
    return $this->$method();
  }

  // ...
}
```

```php
// controllers/comment.php
<?php
class CommentController extends Controller {
  function __construct() {
    $this->db = new Db('comments');
  }

  function post() {
    parent::respond($this->db->insert([
      'user_id' => getSessionUser('id'),
      'content' => $this->req['queries']['content']
    ]));
  }

  // ...
}
```
