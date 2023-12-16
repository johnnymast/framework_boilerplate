@if (is_array($messages))
    @foreach($messages as $message)
    <div class="w-full p-3 mt-8 bg-green-100 rounded flex items-center my-4">
        <div tabindex="0" aria-label="success icon" role="img"
             class="focus:outline-none w-8 h-8 border rounded-full border-green-200 flex flex-shrink-0 items-center justify-center">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.66674 10.1147L12.7947 3.98599L13.7381 4.92866L6.66674 12L2.42407 7.75733L3.36674 6.81466L6.66674 10.1147Z"
                      fill="#047857"/>
            </svg>
        </div>
        <div class="pl-3 w-full">
            <div class="flex items-center justify-between">
                <p tabindex="0" class="focus:outline-none text-sm leading-none text-green-700">{{$message}}</p>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="w-full p-3 mt-8 bg-green-100 rounded flex items-center my-4">
        <div tabindex="0" aria-label="success icon" role="img"
             class="focus:outline-none w-8 h-8 border rounded-full border-green-200 flex flex-shrink-0 items-center justify-center">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.66674 10.1147L12.7947 3.98599L13.7381 4.92866L6.66674 12L2.42407 7.75733L3.36674 6.81466L6.66674 10.1147Z"
                      fill="#047857"/>
            </svg>
        </div>
        <div class="pl-3 w-full">
            <div class="flex items-center justify-between">
                <p tabindex="0" class="focus:outline-none text-sm leading-none text-green-700">{{$messages}}</p>
            </div>
        </div>
    </div>
@endif