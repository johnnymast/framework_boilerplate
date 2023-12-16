{!! '<' !!}?php
namespace App\Http\Controller;

@if (count($functions))
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
@endif

class {{$name}} extends Controller
{
@if (count($functions))
@foreach($functions as $function)
{{"\n"}}{{"\t"}}public function {{$function}}(Request $request, Response $response)
{{"\t"}}{
{{"\t"}}{{"\t"}}// TODO: Implement {{$function}}() method.
{{"\t"}}}
@endforeach
@endif}