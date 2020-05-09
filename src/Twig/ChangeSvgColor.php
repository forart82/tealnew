<?php

/**
 * @file
 * Contains a class to change svg color form email icone
 */


namespace App\Twig;

use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ChangeSvgColor extends AbstractExtension
{
  public function getFunctions(): array
  {
    return [
      new TwigFunction('change_svg_color', [$this, 'changeSvgColor'])
    ];
  }
  public function changeSvgColor(string $svg, User $user, string $svgType): string
  {
    switch ($svgType) {
      case 'email':
        if ($user->getIsNew()) {
          $svg = preg_replace('/fill="#[a-zA-Z0-9]+"/', 'fill="#ff6420"', $svg);
          return $svg;
        }
        $svg = preg_replace('/fill="#[a-zA-Z0-9]+"/', 'fill="#00aaaa"', $svg);
        return $svg;
      case 'admin':
        if ($user->getRoles()[0]=="ROLE_SUPER_ADMIN") {
          $svg = preg_replace('/fill="#[a-zA-Z0-9]+"/', 'fill="#ff0000"', $svg);
          return $svg;
        }
        if ($user->getRoles()[0]=="ROLE_ADMIN") {
          $svg = preg_replace('/fill="#[a-zA-Z0-9]+"/', 'fill="#ff6420"', $svg);
          return $svg;
        }
        $svg = preg_replace('/fill="#[a-zA-Z0-9]+"/', 'fill="#00aaaa"', $svg);
        return $svg;
      default:
        # code...
        break;
    }
    return $svg;
  }
}
