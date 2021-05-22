<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGviral</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script  src="{{asset('css/font.js')}}"></script>
</head>
<body>
<div id="app">
<App
:allusers="{{ $users }}" :authUser="{{ auth()->user() }}" turn_url="{{ env('TURN_SERVER_URL') }}"
></App>
</div>
<script src="{{mix('js/app.js')}}"></script>
<script>
    window.user = {
        id: '{{auth()->id()}}',
        name:'{{auth()->user()->name}}',
    }
    window.csrfToken = "{{csrf_token()}}";
        /*Toggle dropdown list*/
        function toggleDD(myDropMenu) {
            document.getElementById(myDropMenu).classList.toggle("invisible");
        }
        /*Filter dropdown options*/
        function filterDD(myDropMenu, myDropMenuSearch) {
            var input, filter, ul, li, a, i;
            input = document.getElementById(myDropMenuSearch);
            filter = input.value.toUpperCase();
            div = document.getElementById(myDropMenu);
            a = div.getElementsByTagName("a");
            for (i = 0; i < a.length; i++) {
                if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                    a[i].style.display = "";
                } else {
                    a[i].style.display = "none";
                }
            }
        }
        // Close the dropdown menu if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
                var dropdowns = document.getElementsByClassName("dropdownlist");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (!openDropdown.classList.contains('invisible')) {
                        openDropdown.classList.add('invisible');
                    }
                }
            }
        }
       function playPauseVideo(post_id){
         alert(post_id);
        }
    </script>
</body>
</html>