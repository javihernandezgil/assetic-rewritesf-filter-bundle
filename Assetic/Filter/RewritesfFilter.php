<?php
namespace Jhg\AsseticRewritesfFilterBundle\Assetic\Filter;
 
use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Jhg\AsseticRewritesfFilterBundle\Assetic\Util\ReferenceUtils;
 
class RewritesfFilter implements FilterInterface
{
    public function filterLoad(AssetInterface $asset)
    {
    }
    
    protected function filterReferences($content, $callback, $limit = -1, &$count = 0)
    {
    	return ReferenceUtils::filterReferences($content, $callback, $limit, $count);
    }
    
    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();
		   
        $content = $this->filterReferences($asset->getContent(), function($matches) {
        	
        	global $kernel;
        	$resource = $kernel->locateResource($matches[1]);
        	
        	return str_replace($matches[1],$resource,$matches[0]);
        });
        
        $asset->setContent($content);
    }
}