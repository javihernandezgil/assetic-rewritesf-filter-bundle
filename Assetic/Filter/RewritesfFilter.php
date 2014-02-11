<?php
namespace Jhg\AsseticRewritesfFilterBundle\Assetic\Filter;
 
use Assetic\Asset\AssetInterface;
use Assetic\Filter\FilterInterface;
use Jhg\AsseticRewritesfFilterBundle\Assetic\Util\ReferenceUtils;
 
class RewritesfFilter implements FilterInterface
{
    public function filterLoad(AssetInterface $asset)
    {
        global $kernel;
        
        $content = $this->filterReferences($asset->getContent(), function($matches) use ($kernel) {
            // obtains resource using bundle referenced order (app/Resources, bundle)
            $resource = $kernel->locateResource($matches[1],$kernel->getRootDir().'/Resources');
            
            return str_replace($matches[1],$resource,$matches[0]);
        });
        
        $asset->setContent($content);
    }
    
    protected function filterReferences($content, $callback, $limit = -1, &$count = 0)
    {
    	return ReferenceUtils::filterReferences($content, $callback, $limit, $count);
    }
    
    public function filterDump(AssetInterface $asset)
    {
        
    }
}