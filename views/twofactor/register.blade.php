@extends('layout.main')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Two-Factor Registration</div>

                <form method="POST">
                @csrf
                <div class="card-body" style="text-align: center;">
                    <p>Set up your two factor authentication by scanning the barcode below. Alternatively, you can use the code</p>
                    <span class="form-control">{{ $secret }}</span><br />
                    <div>
                        {!! $QR_Image !!}
                    </div>
                    <br />
                    <p class="alert alert-warning">You must set up your authenticator app before continuing. You will be unable to login otherwise.</p>
                    <div>
                        <input type="hidden" value="{{ $secret }}" name="twofactor_secret" />
                        <input type="submit" class="btn btn-success" value="Finish Setup" />
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop