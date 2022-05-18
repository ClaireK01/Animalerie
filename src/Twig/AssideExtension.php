<?php

namespace App\Twig;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AssideExtension extends AbstractExtension
{
    public function __construct(private CategoryRepository $categoryRepository,){
        
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_all_categories', [$this, 'get_all_categories']),
        ];
    }

    public function get_all_categories()
    {
       $categoryEntities = $this->categoryRepository->findAll();
       return $categoryEntities; 
    }
}
