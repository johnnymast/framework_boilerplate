@extends('layouts.blank')

@section('title', $message)

@section('content')
    <main class="h-screen w-full flex flex-col justify-center items-center bg-[#1A2238]">
        <h1 class="text-9xl font-extrabold text-white tracking-widest">CSRF</h1>
        <div class="bg-[#FF6A3D] px-2 text-sm rounded rotate-12 absolute">
            {{$message}}
        </div>
        <button class="mt-5">
            <div class="relative inline-block text-sm font-medium text-[#FF6A3D] group active:text-orange-500 focus:outline-none focus:ring"><span class="absolute inset-0 transition-transform translate-x-0.5 translate-y-0.5 bg-[#FF6A3D] group-hover:translate-y-0 group-hover:translate-x-0"></span>
            </div>
        </button>
    </main>
@endsection

