@component('mail::message')
    # Hola!
    ## Tu artículo ha recibido nuevo comentario.
    {{$comment->text}}
    ![Imagen del Artículo]({{asset('storage/' . $comment->articl->image)}}"Imagen")
    @component('mail::button', ['url' => URL::to('/')])
    Ver comentario
    @endcomponent

    @component('mail::panel')
    This is the panel content.
    @endcomponent

    @component('mail::table')
    | Laravel       | Table         | Example  |
    | ------------- |:-------------:| --------:|
    | Col 2 is      | Centered      | $10      |
    | Col 3 is      | Right-Aligned | $20      |
    @endcomponent

    @component('mail::subcopy')
    This is the subcopy content.
    @endcomponent
    Gracias,<br>
    {{ config('app.name') }}
@endcomponent
