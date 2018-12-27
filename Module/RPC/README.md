```php
$client = new \Module\RPC\Domain\Client('http://zodream.localhost/rpc/server');
$data = $client->invoke('func', 1);
dd($data);
```