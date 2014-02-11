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

        $sourceBase = $asset->getSourceRoot();
        $sourcePath = $asset->getSourcePath();
        $targetPath = $asset->getTargetPath();
        
        if (null === $sourcePath || null === $targetPath || $sourcePath == $targetPath) {
        	return;
        }
        
        // learn how to get from the target back to the source
        if (false !== strpos($sourceBase, '://')) {
        	list($scheme, $url) = explode('://', $sourceBase.'/'.$sourcePath, 2);
        	list($host, $path) = explode('/', $url, 2);
        
        	$host = $scheme.'://'.$host.'/';
        	$path = false === strpos($path, '/') ? '' : dirname($path);
        	$path .= '/';
        } else {
        	// assume source and target are on the same host
        	$host = '';
        
        	// pop entries off the target until it fits in the source
        	if ('.' == dirname($sourcePath)) {
        		$path = str_repeat('../', substr_count($targetPath, '/'));
        	} elseif ('.' == $targetDir = dirname($targetPath)) {
        		$path = dirname($sourcePath).'/';
        	} else {
        		$path = '';
        		while (0 !== strpos($sourcePath, $targetDir)) {
        			if (false !== $pos = strrpos($targetDir, '/')) {
        				$targetDir = substr($targetDir, 0, $pos);
        				$path .= '../';
        			} else {
        				$targetDir = '';
        				$path .= '../';
        				break;
        			}
        		}
        		$path .= ltrim(substr(dirname($sourcePath).'/', strlen($targetDir)), '/');
        	}
        }
		   
        $content = $this->filterReferences($asset->getContent(), function($matches) use ($host, $path) {
        	
        	global $kernel;
        	$resource = $kernel->locateResource($matches[1]);
        	
        	return str_replace($matches[1],$resource,$matches[0]);
        });
        
        $asset->setContent($content);
    }
}