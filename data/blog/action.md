# Les actions
Celles-ci peuvent être appellé dans une vue par la méthode static qu'il convient d'instancier dans un fichier se trouvant dans le dossier `application/include/action/`
par exemple:
```php
<?php  
  
use MVC\Classe\HttpMethodRequete;  
use MVC\Classe\Implement\Action;  
use MVC\Classe\Url; 
  
class DefaultAction extends Action  
{  
  public function default($data)  
 {  
	  /**your action algorythm**/  
	  if (isset($data[0])) {  
		  $var1 = $data[0];  
	  } else {  
		  $var1 = 1;  
	  }
	  if (isset($data[1])) {  
		  $var2 = $data[1];  
	  } else {  
		  $var2 = 2;  
	  }
	  if (isset($data[2])) {  
		  $var3 = $data[2];  
	  } else {  
		  $var3 = 3;  
	  }  
	  return $this->render('action', array('var1' => $var1, 'var2' => $var2, 'var3' => $var3));  
 }  
  public function variableSlug($data)  
 {  
	 /**your action algorythm**/  
	 if (isset($data[0])) {  
		  $var1 = $data[0];  
	 } else {  
		  $var1 = 1;  
	 }
	 if (isset($data[1])) {  
		  $var2 = $data[1];  
	 } else {  
		  $var2 = 2;  
	 }
	 if (isset($data[2])) {  
		  $var3 = $data[2];  
	 } else {  
		  $var3 = 3;  
	 }  
	 ob_start()
	 print_r($data)
	 return ob_get_clean();
 }  
  public function makeHttp11($data)  
 {  
	  $data = array('myval' => 25);  
	  
	  $request = new HttpMethodRequete();  
	  $request->setUrl(Url::absolute_link_rewrite(false,'accueil',['var10'=>'val10']))->get($data);  
	  $request->setUrl(Url::absolute_link_rewrite(false,'accueil',['var10'=>'val10']))->post($data);     				
	  $request->setUrl(Url::absolute_link_rewrite(false, 'accueil', ['var10' => 'val10']))->put($data);  
	  $request->setUrl(Url::absolute_link_rewrite(false,'accueil',['var10'=>'val10']))->delete($data);  
  }  
}
```

avec cet accès dans la vue:
```php
  
{{\MVC\Classe\ControlleurAction::inserer('default',[])}}  
{{\MVC\Classe\ControlleurAction::inserer('default.default',[4,5,6])}}  
{{\MVC\Classe\ControlleurAction::inserer('default.variableSlug',['var1','var2','var3'])}}  
  
{{\MVC\Classe\ControlleurAction::inserer('default.makeHttp11',[])}}
```

il faut absolument que l'action retourne du texte soit par la la méthode `render` soit par un `système de tampon`