<?php
/**
 * Created by PhpStorm.
 * User: tacsiazuma
 * Date: 2017.10.26.
 * Time: 22:52
 */

namespace App\Http\ViewModel\Provider;


use App\Http\ViewModel\Factory\LinkFactory;
use App\Http\ViewModel\Menu;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Translation\Translator;

class StaticMenuProvider implements MenuProvider
{

    private $repository;
    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var Log
     */
    private $logger;
    /**
     * @var LinkFactory
     */
    private $linkFactory;

    /**
     * StaticMenuProvider constructor.
     * @param Repository $repository
     * @param Translator $translator
     * @param Log $logger
     * @param LinkFactory $linkFactory
     */
    public function __construct(Repository $repository, Translator $translator, Log $logger,
            LinkFactory $linkFactory)
    {
        $this->repository = $repository;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->linkFactory = $linkFactory;
    }

    /**
     * Returns a Menu built for the sidebar.
     * @return Menu
     */
    function provide()
    {
        $configItems = $this->repository->get("view.menu");
        if ($configItems == null) {
            $configItems = [];
            $this->logger->error("Menu provider failed to retrieve menu from config.");
        }
        $menuItems = [];
        foreach ($configItems as $configItem) {
            $menuItems[] = $this->linkFactory->create($configItem["url"],
                $this->translator->trans($configItem["title"]));
        }
        return new Menu($menuItems);
    }
}