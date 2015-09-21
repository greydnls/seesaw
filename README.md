# Seesaw
Routing, backwards and forwards

Seesaw is a reverse routing ornamentation that sits on top of [League/Route](http://github.com/thephpleague/route). All of the functionality of route is available, as well as the ability to reverse routes. 

### Usage: 
#### Adding a named route. 
```
  $seesaw = new Seesaw();
  $seesaw->addNamedRoute('JimBob', 'GET', 'url/jim/bob', function(){});
  echo $this->route('JimBob'); // will output: /url/jim/bob
```

#### Using a base url: 
```
$seesaw->setBaseUrl('http://yolo.com');
$seesaw->addNamedRoute('JimBob', 'GET', 'url/jim/bob', function(){});
echo $this->route('JimBob'); // will output http://yolo.com/url/jim/bob
```

#### Relative and Secure URLs
```
$seesaw->setBaseUrl('http://yolo.com');
$seesaw->addNamedRoute('JimBob', 'GET', 'url/jim/bob', function(){});
echo $this->route('JimBob')->secure(); // will output https://yolo.com/url/jim/bob
echo $this->route('JimBob')->relative(); // will output /url/jim/bob
```

#### Using paramterized routes
```
$seesaw->addNamedRoute('JimBob', 'GET', 'url/jim/bob/{id}', function(){});
echo $seesaw->route('JimBob', [123]); // will output /url/jim/bob/123;
```
