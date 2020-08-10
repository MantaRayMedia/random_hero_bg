## Caching for anonymous users

To solve the problem with the caching for anonymous users, we have been working on 3 different files

```pubmlst/web/themes/custom/pubmlst/templates/nodes/node--front.html.twig
pubmlst/web/themes/custom/pubmlst/pubmlst.theme
pubmlst/web/modules/custom/random_hero_bg/
```

### node--front.html.twig
We have different random background colours to apply on the hero section, to randomise the colours without cache the results all the time, we used a module with the randomise the colours

```{% set hero_rand_test = random(10,100) %}
{% set hero_rand_bg = ['bg-blue','bg-purple','bg-teal','bg-navy'] | randomBackground(hero_rand_test) %}
```

### random_hero_bg custom module
On RandomHeroBG.php we have two functions, one function to clear first the cache for Anonymous users

```public function clearCache()
{
  \Drupal::service('page_cache_kill_switch')->trigger();
  \Drupal::service('cache.render')->invalidateAll();
  
  // clear caches for anonymous 
  Cache::invalidateTags(['HIT']);
}
```

And then another function to delete the random background colour before randomise again the values.

```
public function randomBackground(array $input, $number)
{
  $this->clearCache();
  $key = array_rand($input);
  return $input[$key];
}
```

To double check if it is working, check if the number is changing with this code inside the randomBackground Functions

```
var_dump($number, $key);
```

Check this link for more information about [cache-tags](https://www.drupal.org/docs/drupal-apis/cache-api/cache-tags)

### pubmlst.theme
Add a preprocess function to clear caches for this specific node type, cleaning the session cache and the variables used on the random

```
function pubmlst_preprocess_node__front(&$variables){
  $variables['#cache'] = [
    'max-age' => 0,
    'contexts' => ['session']
  ];
  $variables['hero_rand_bg']['#cache']['max-age'] = 0;
  $variables['hero_rand_test']['#cache']['max-age'] = 0;
}
```