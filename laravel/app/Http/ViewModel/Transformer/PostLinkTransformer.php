<?php

namespace App\Http\ViewModel\Transformer;


use App\Http\ViewModel\Link;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Translation\Translator;

class PostLinkTransformer
{
    const READ_MORE_KEY = "main_page.read_more";
    const POST_BASE_URL_KEY = "view.main_page.post_base_url";

    /**
     * @var Translator
     */
    private $translator;
    /**
     * @var Repository
     */
    private $configRepository;

    /**
     * PostLinkTransformer constructor.
     * @param Translator $translator
     * @param Repository $configRepository
     */
    public function __construct(Translator $translator, Repository $configRepository)
    {
        $this->translator = $translator;
        $this->configRepository = $configRepository;
    }

    /**
     * Transform a slug into a link for the given post.
     * @param $slug
     * @return Link
     */
    public function transform($slug) {
        $baseUrl = $this->configRepository->get(self::POST_BASE_URL_KEY);
        return new Link($baseUrl.$slug, $this->translator->trans(self::READ_MORE_KEY));
    }

}