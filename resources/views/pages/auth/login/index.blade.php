@extends('components.auth-layout')
@section('content')
<div class="container">
  <div class="row">
    <div class="d-flex align-items-center justify-content-center vh-100">
      <div class="col-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">{{ config('app.name') }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Please log in to your account</h6>
            <hr class="hr" />
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="m-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
            <form method="POST">
              @csrf
              <input type="hidden" name="type" value="web">
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" class="form-control form-control-lg" placeholder="Please enter your email" name="email" />
                <label class="form-label">Email</label>
              </div>
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" class="form-control form-control-lg" placeholder="Please enter your password" name="password" />
                <label class="form-label">Password</label>
              </div>
              <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block">Log in</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection