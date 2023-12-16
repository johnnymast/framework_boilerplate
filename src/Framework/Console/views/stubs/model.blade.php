{!! '<' !!}?php
namespace App\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Table(name: '{{$table}}')@if (!empty($repositoryClass)), Entity(repositoryClass: "App\Repository\{{$repositoryClass}}")@endif]
final class {{$name}}
{
{{"\t"}}// Todo add properties and methods
}
