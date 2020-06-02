@extends("layout")
@section("content")
    <div class="body"></div>
    <div class="grad"></div>
    <div class="header">
        <div>Log<span>in</span></div>
    </div>
    <br>
    <div class="login">
        <input type="text" placeholder="Username" name="user"><br>
        <input type="password" placeholder="Password" name="password"><br>
        <input type="button" value="Login">
        <p class="forgot">Forgot Password? <a class="forgot"> Click here</a></p>
    </div>
@endsection

