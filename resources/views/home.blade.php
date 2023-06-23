@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif


                    {{-- input file --}}

                    <form action="{{ route('upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" id="file">
                        <button type="submit">Upload</button>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
