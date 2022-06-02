@extends('layouts.myapp')
@section('title','Theme Setting')
<style>
    div.theme{
        display:flex;
    }
    /* The theme_container */
    .theme_container {
    display: inline-block;
    position: relative;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    }

    /* Hide the browser's default radio button */
    .theme_container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    }
</style>
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="fa fa-tachometer"></i>
            </div>
            <div>
                Theme Setting
                <div class="page-title-subheading">
                    Choose Available colors to change the application theme
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-card mb-3 card">
    <div class='card-header'>
        Choose Color Theme
    </div>
    <div class="card-body">
        <form class="theme" method="POST" action="/theme">
        @csrf
            <label class="theme_container"><p title="Red Theme" class="btn themeRed switchTheme" 
            value="themeRed" data-class="themeRed"></p>
            <input type="radio"  name="name" value="themeRed" >
            </label>

            <label class="theme_container"><p title="Grey Theme" class="btn bg-secondary switchTheme"  
            value="bg-secondary" data-class="bg-secondary"></p>
            <input type="radio"  name="name" value="bg-secondary">
            </label>

            <label class="theme_container"><p title="Green Theme" class="btn themeGreen switchTheme"  
            value="themeGreen" data-class="themeGreen"></p>
            <input type="radio"  name="name" value="themeGreen">
            </label>

            <label class="theme_container"><p title="Black Theme" class="btn themeblackShade switchTheme"  
            value="themeblackShade" data-class="themeblackShade"></p>
            <input type="radio"  name="name" value="themeblackShade">
            </label>

            <label class="theme_container"><p title="Dark Theme" class="btn themedark switchTheme"  
            value="themedark" data-class="themedark"></p>
            <input type="radio"  name="name" value="themedark">
            </label>

            <label class="theme_container"><p title="Default Theme" class="btn themePurple switchTheme"  
            value="themePurple" data-class="themePurple"></p>
            <input type="radio"  name="name" value="themePurple">
            </label>

            <label class="theme_container"><p title="Default Theme" class="btn themeLime switchTheme"  
            value="themeLime" data-class="themeLime"></p>
            <input type="radio"  name="name" value="themeLime">
            </label>
            
            <label class="theme_container"><p title="Default Theme" class="btn themeLime1 switchTheme"  
            value="themeLime1" data-class="themeLime1"></p>
            <input type="radio"  name="name" value="themeLime1">
            </label>

            <label class="theme_container"><p title="Default Theme" class="btn themeLime2 switchTheme"  
            value="themeLime2" data-class="themeLime2"></p>
            <input type="radio"  name="name" value="themeLime2">
            </label>

            <input type="submit" class="btn btn-success" value="OK" name="submit">
        </form>
    </div>
</div>
@endsection
