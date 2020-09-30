<?php

namespace Sim\Abstracts\Mvc\Controller\Renderer;

use Jenssegers\Agent\Agent;
use Sim\Exceptions\ConfigManager\ConfigNotRegisteredException;
use Sim\Interfaces\ConfigManager\IConfig;
use Sim\Interfaces\IFileNotExistsException;
use Sim\Interfaces\IInvalidVariableNameException;
use Sim\Interfaces\Mvc\Controller\Renderer\IViewRenderer;
use Sim\Interfaces\PathManager\IPath;
use Sim\Traits\TraitGeneral;
use Sim\Utils\ArrayUtil;

abstract class AbstractViewRenderer implements IViewRenderer
{
    use TraitGeneral;

    /**
     * @var $layout string
     */
    protected $layout;

    /**
     * @var $template string
     */
    protected $template;

    /**
     * @var $arguments array
     */
    protected $arguments = [];

    /**
     * @var $rendered string
     */
    protected $rendered;

    /**
     * @var IPath $path
     */
    protected $path;

    /**
     * @var IConfig $config
     */
    protected $config;

    /**
     * AbstractViewRenderer constructor.
     * @param string $layout
     * @param string $template
     * @param array $arguments
     */
    public function __construct(string $layout, string $template = '', array $arguments = [])
    {
        $this->path = path();
        $this->config = config();

        $this->layout = $layout;
        $this->template = $template;
        $this->arguments = is_array($arguments) ? $arguments : [];
    }

    /**
     * Assign some css and js file to css and js files with alias of include file key
     *
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    protected function renderLayoutAssets(): void
    {
        // Get includes config file items
        $includes = $this->config->get('includes');

        // Check if we have any configuration
        if (count($includes)) {
            // Get main alias name
            // EXP.
            //   file/to/template/test.php => test
            // Replace all slashes and backslashes with DIRECTORY_SEPARATOR
            $templatePath = $this->slashConverter($this->template);
            // It must be in two lines because it get a notice that it must pass by reference to
            // slashConverter function
            $designPath = design_path();
            $designPath = $this->slashConverter($designPath);

            // Remove views path from template path
            $mainAlias = str_replace($designPath, '', $templatePath);
            // Explode it by dot to remove extension
            $mainAlias = explode('.', $mainAlias);
            // Pop extension out
            array_pop($mainAlias);
            // Implode all parts and we have the key
            $mainAlias = implode('/', $mainAlias);

            // Create variables with platform configs
            $mobileConfig = $includes['mobile'][$mainAlias] ?? [];
            $mobileCommonConfig = $includes['mobile']['common'] ?? [];
            $tabletConfig = $includes['tablet'][$mainAlias] ?? [];
            $tabletCommonConfig = $includes['tablet']['common'] ?? [];
            $desktopConfig = $includes['desktop'][$mainAlias] ?? [];
            $desktopCommonConfig = $includes['desktop']['common'] ?? [];

            // Detect which platform must be use
            $agent = new Agent();
            $isMobile = $agent->isMobile();
            $isTablet = $agent->isTablet();
//            $isDesktop = $agent->isDesktop();

            // Include file decision
            // This decision work as follow:
            //   All included key value from upper priority, will replace to lower priority
            //   e.g. mobile will replace in tablet and desktop and tablet will replace in desktop and desktop will be itself.
            if ($isMobile) {
                // Main
                $mainAliasIncludeMerged = array_merge_recursive($desktopConfig, $tabletConfig, $mobileConfig);
                $mainAliasInclude = array_replace_recursive($desktopConfig, $tabletConfig, $mobileConfig);
                // Common
                $commonAliasInclude = array_replace_recursive($desktopCommonConfig, $tabletCommonConfig, $mobileCommonConfig);
            } elseif ($isTablet) {
                // Main
                $mainAliasIncludeMerged = array_merge_recursive($desktopConfig, $tabletConfig);
                $mainAliasInclude = array_replace_recursive($desktopConfig, $tabletConfig);
                // Common
                $commonAliasInclude = array_replace_recursive($desktopCommonConfig, $tabletCommonConfig);
            } else { // if($agent->isDesktop())
                $mainAliasInclude = $desktopConfig;
                $commonAliasInclude = $desktopCommonConfig;
            }

            // Mix Main and Common in one place
            $mainAliasInclude = array_replace_recursive($commonAliasInclude, $mainAliasInclude);

            if ($isMobile || $isTablet) {
                // Replace required js and css and other things that are not string with merged one
                foreach ($mainAliasInclude as $key => &$item) {
                    if (is_array($item) && isset($mainAliasIncludeMerged[$key])) {
                        if ('js' === $key) {
                            $js = $mainAliasIncludeMerged['js'] ?? [];
                            $item = ArrayUtil::uniqueRecursive($js);
                        } elseif ('css' === $key) {
                            $css = $mainAliasIncludeMerged['css'] ?? [];
                            $item = ArrayUtil::uniqueRecursive($css);
                        } else {
                            $item = $mainAliasIncludeMerged[$key] ?? '';
                        }
                    }
                }
            }

            // If alias items are not set, we have no reason to continue
            if (!isset($mainAliasInclude) || empty($mainAliasInclude)) return;

            // Remove js and css files duplicate according to src and href and
            // create a multiline string to user just echo this and don't need
            // to do foreach all by itself
            $matchPattern = '(.+?)';

            //------------------
            // JS unique process
            //------------------
            $js = $mainAliasInclude['js'] ?? [];
            $jsStrTmp = [];
            $jsSrcTmp = [];
            $srcPattern = '/src="' . $matchPattern . '"/';
            // Make js files unique
            foreach ($js as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if (is_string($v2)) {
                            preg_match($srcPattern, htmlspecialchars_decode($v2), $matches);
                            $match = $matches[1] ?? '';

                            if (!empty($match) && !in_array($match, $jsSrcTmp)) {
                                $jsSrcTmp[] = $match;
                                if (is_int($k) && is_string($v2)) {
                                    $jsStrTmp['top'][$k2] = $v2;
                                } else {
                                    $jsStrTmp[$k][$k2] = $v2;
                                }
                            }
                        }
                    }
                } elseif (is_string($v)) {
                    preg_match($srcPattern, htmlspecialchars_decode($v), $matches);
                    $match = $matches[1] ?? '';
                    if (!empty($match) && !in_array($match, $jsSrcTmp)) {
                        $jsSrcTmp[] = $match;
                        if (is_int($k)) {
                            $jsStrTmp['top'][] = $v;
                        } else {
                            $jsStrTmp[$k] = $v;
                        }
                    }
                }
            }

            //-------------------
            // CSS unique process
            //-------------------
            $css = $mainAliasInclude['css'] ?? [];
            $cssStrTmp = [];
            $cssHrefTmp = [];
            $hrefPattern = '/href="' . $matchPattern . '"/';
            // Make js files unique
            foreach ($css as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if (is_string($v2)) {
                            preg_match($hrefPattern, htmlspecialchars_decode($v2), $matches);
                            $match = $matches[1] ?? '';
                            if (!empty($match) && !in_array($match, $cssHrefTmp)) {
                                $cssHrefTmp[] = $match;
                                if (is_int($k) && is_string($v2)) {
                                    $cssStrTmp['top'][$k2] = $v2;
                                } else {
                                    $cssStrTmp[$k][$k2] = $v2;
                                }
                            }
                        }
                    }
                } else if (is_string($v)) {
                    preg_match($hrefPattern, htmlspecialchars_decode($v), $matches);
                    $match = $matches[1] ?? '';
                    if (!empty($match) && !in_array($match, $cssHrefTmp)) {
                        $cssHrefTmp[] = $match;
                        if (is_int($k)) {
                            $cssStrTmp['top'][] = $v;
                        } else {
                            $cssStrTmp[$k] = $v;
                        }
                    }
                }
            }

            // Add unique values to its place
            $mainAliasInclude['js'] = $jsStrTmp;
            $mainAliasInclude['css'] = $cssStrTmp;

            // Put all js and css file together as a string
            // to help user just echo it not iterate on it
            $mainJsTmp = [];
            foreach ($mainAliasInclude['js'] as $k => $js) {
                if (is_array($js)) {
                    foreach ($js as $k2 => $v) {
                        if (is_string($v)) {
                            if (!isset($mainJsTmp[$k])) $mainJsTmp[$k] = '';
                            $mainJsTmp[$k] .= htmlspecialchars_decode($v) . "\n";
                        }
                    }
                }
            }
            $mainCssTmp = [];
            foreach ($mainAliasInclude['css'] as $k => $css) {
                if (is_array($css)) {
                    foreach ($css as $k2 => $v) {
                        if (is_string($v)) {
                            if (!isset($mainCssTmp[$k])) $mainCssTmp[$k] = '';
                            $mainCssTmp[$k] .= htmlspecialchars_decode($v) . "\n";
                        }
                    }
                }
            }
            $mainAliasInclude['js'] = $mainJsTmp;
            $mainAliasInclude['css'] = $mainCssTmp;

            // Load all values in the keys of config
            foreach ($mainAliasInclude as $key => $value) {
                $this->arguments[$key] = $value;
            }
        }
    }

    /**
     * Return rendered page
     *
     * @return string
     * @throws ConfigNotRegisteredException
     * @throws IFileNotExistsException
     * @throws IInvalidVariableNameException
     */
    public function render(): string
    {
        $this->renderLayoutAssets();
        $this->renderTemplate();
        $this->renderLayout();
        return $this->rendered;
    }
}