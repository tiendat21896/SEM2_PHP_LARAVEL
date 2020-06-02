@extends("layout")
@section("content")
    <div class="body"></div>
    <div class="grad"></div>
    <div class="header">
        <div>Register<span></span></div>
    </div>
    <br>
    <div class="login">
        <input type="text" placeholder="Name" name="Name"><br>
        <input type="email" placeholder="Email" name="email"style="margin-top: 10px"><br>
        <input type="password" placeholder="Password" name="password"><br>
        <input type="button" value="Register">
    </div>
@endsection
