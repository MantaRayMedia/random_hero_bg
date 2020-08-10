<?php

namespace Drupal\random_hero_bg\TwigExtension;

use Drupal\Core\Cache\Cache;

class RandomHeroBG extends \Twig_Extension {

  public function getFilters()
  {
    return [
      new \Twig_SimpleFilter('randomBackground', [$this, 'randomBackground']),
    ];
  }
  public function getName()
  {
    return 'bg_filter';
  }

  /**
   * Check this link for information about caching
   * https://www.drupal.org/docs/drupal-apis/cache-api/cache-tags
   * 
   * @param array $input
   * @param $number
   * @return mixed
   */
  public function randomBackground(array $input, $number)
  {
    $this->clearCache();
    
    $key = array_rand($input);
    
    // double check, print variables >> check the number is changing <<
    // var_dump($number, $key);
    
    return $input[$key];
  }
  
  
  
  public function clearCache()
  {
    \Drupal::service('page_cache_kill_switch')->trigger();
    \Drupal::service('cache.render')->invalidateAll();
    
    // clear caches for anonymous 
    Cache::invalidateTags(['HIT']);
    
    // clear caches for specific node
    // ::invalidateTags($node->getCacheTags())
  }

}
