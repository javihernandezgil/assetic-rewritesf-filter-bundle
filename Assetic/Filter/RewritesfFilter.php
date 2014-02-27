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

        $sourceBase = $asset->getSourceRoot();
        $sourcePath = $asset->getSourcePath();
        $resourceAbsolutePath = pathinfo("$sourceBase/$sourcePath",PATHINFO_DIRNAME);

        $content = $asset->getContent();

        $content = $this->filterImportReferences($content, function($matches) use ($kernel,$resourceAbsolutePath) {
            // obtains resource using bundle referenced order (app/Resources, bundle)
            $resource = $kernel->locateResource($matches[1],$kernel->getRootDir().'/Resources');
            $relativeResource = ReferenceUtils::pathRelative2FilePath($resourceAbsolutePath,$resource);

            return str_replace($matches[1],$relativeResource,$matches[0]);
        });

        $content = $this->filterUrlReferences($content, function($matches) use ($kernel) {
            // obtains resource using bundle referenced order (app/Resources, bundle)
            $bundle = $kernel->getBundle($matches[2]);
            $targetDir  = '/bundles/'.preg_replace('/bundle$/', '', strtolower($bundle->getName()));

            $resource = $kernel->locateResource($matches[1],$kernel->getRootDir().'/Resources');
            $resourceSplit = explode('/public/',$resource);

            $resourceUrl = "$targetDir/{$resourceSplit[1]}";

            return str_replace($matches[1],$resourceUrl,$matches[0]);
        });

        $asset->setContent($content);
    }

    protected function filterImportReferences($content, $callback, $limit = -1, &$count = 0)
    {
        return ReferenceUtils::filterImportReferences($content, $callback, $limit, $count);
    }

    protected function filterUrlReferences($content, $callback, $limit = -1, &$count = 0)
    {
        return ReferenceUtils::filterUrlReferences($content, $callback, $limit, $count);
    }

    public function filterDump(AssetInterface $asset)
    {

    }
}