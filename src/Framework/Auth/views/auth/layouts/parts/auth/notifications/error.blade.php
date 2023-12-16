@if (is_array($messages))
    @foreach($messages as $message)
    <div class="w-full p-3 mt-8 bg-red-100 rounded flex items-center my-4">
        <div tabindex="0" aria-label="storage icon" role="img" class="focus:outline-none w-8 h-8 border rounded-full border-red-200 flex items-center flex-shrink-0 justify-center">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 2V14C14 14.1768 13.9298 14.3464 13.8047 14.4714C13.6797 14.5964 13.5101 14.6667 13.3333 14.6667H2.66667C2.48986 14.6667 2.32029 14.5964 2.19526 14.4714C2.07024 14.3464 2 14.1768 2 14V2C2 1.82319 2.07024 1.65362 2.19526 1.5286C2.32029 1.40357 2.48986 1.33334 2.66667 1.33334H13.3333C13.5101 1.33334 13.6797 1.40357 13.8047 1.5286C13.9298 1.65362 14 1.82319 14 2ZM3.33333 10.6667V13.3333H12.6667V10.6667H3.33333ZM10 11.3333H11.3333V12.6667H10V11.3333Z" fill="#B91C1C" />
            </svg>
        </div>
        <div class="pl-3 w-full">
            <div class="flex items-center justify-between">
                <p tabindex="0" class="focus:outline-none text-sm leading-none text-red-700">{{$message}}</p>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="w-full p-3 mt-8 bg-red-100  rounded flex items-center my-4">
        <div tabindex="0" aria-label="storage icon" role="img" class="focus:outline-none w-8 h-8 border rounded-full border-red-200 flex items-center flex-shrink-0 justify-center">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 2V14C14 14.1768 13.9298 14.3464 13.8047 14.4714C13.6797 14.5964 13.5101 14.6667 13.3333 14.6667H2.66667C2.48986 14.6667 2.32029 14.5964 2.19526 14.4714C2.07024 14.3464 2 14.1768 2 14V2C2 1.82319 2.07024 1.65362 2.19526 1.5286C2.32029 1.40357 2.48986 1.33334 2.66667 1.33334H13.3333C13.5101 1.33334 13.6797 1.40357 13.8047 1.5286C13.9298 1.65362 14 1.82319 14 2ZM3.33333 10.6667V13.3333H12.6667V10.6667H3.33333ZM10 11.3333H11.3333V12.6667H10V11.3333Z" fill="#B91C1C" />
            </svg>
        </div>
        <div class="pl-3 w-full">
            <div class="flex items-center justify-between">
                <p tabindex="0" class="focus:outline-none text-sm leading-none text-red-700">{{$messages}}</p>
            </div>
        </div>
    </div>
@endif