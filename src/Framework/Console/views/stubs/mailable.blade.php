{!! '<' !!}?php

declare(strict_types=1);

namespace App\Mail;
use App\Framework\Mail\Mailable;

class {{$name}} extends Mailable
{

{{"\t"}}public function __construct()
{{"\t"}}{
{{"\t"}}}

{{"\t"}}/**
{{"\t"}} * Return the subject of the email.
{{"\t"}} *
{{"\t"}} * @return string
{{"\t"}} */
{{"\t"}}public function subject(): string
{{"\t"}}{
{{"\t"}}    return 'Test Mail';
{{"\t"}}}

{{"\t"}}/**
{{"\t"}} * Return a array of attachments.
{{"\t"}} *
{{"\t"}} * @return array
{{"\t"}} */
{{"\t"}}public function attachments(): array
{{"\t"}}{
{{"\t"}}    return [];
{{"\t"}}}

{{"\t"}}/**
{{"\t"}} * Return the content of the email.
{{"\t"}} *
{{"\t"}} * @return string
{{"\t"}} */
{{"\t"}}public function content(): string
{{"\t"}}{
{{"\t"}}    return viewAsText('emails.{{$view}}',  ['name' => 'Test Name']);
{{"\t"}}}
}