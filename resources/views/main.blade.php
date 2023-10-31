<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>

        .fc-event-main {
            overflow: auto;
        }

        body {
            overflow: hidden;
        }

        table[role="grid"] {
            overflow-x: scroll;
        }

        tr {
            height: 80px;
        }

        th[role="columnheader"] {
            width: 100px;
        }

        td[role="gridcell"] {
            width: 100px;
        }

    </style>
</head>
<body>
@can('create', new App\Models\Room())
    <button class="btn btn-success" id="create_room">Create Room</button>
@endcan
<div id="calendar"></div>

@can('create', new App\Models\Event())
    <div id="exampleModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select id="title">
                            <option value="Driving">Driving</option>
                            <option value="Roar Sign">Roar Sign</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <select id="format">
                            <option value="individual">Individual</option>
                            <option value="group">Group</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <select id="users">
                            @foreach(App\Models\User::where('status', '0')->get() as $user)
                                <option value={{str_replace(" ", "-", $user->name)}}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Start</label>
                        <input  class="form-control"
                                type="datetime-local"
                                id="start"/>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">End</label>
                        <input  class="form-control"
                                type="datetime-local"
                                id="end"/>
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endcan

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.9/index.global.min.js'></script>
<script>


    async function deleteRoom(roomId) {
        try {
            const response = await fetch(`http://localhost/api/rooms/${roomId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                console.log('Room deleted successfully');
            } else {
                console.error('Failed to delete room');
            }

            location.reload()

        } catch (error) {
            console.error('Error deleting room:', error);
        }
    }


    async function getEventById(id) {
        let event = await fetch('http://localhost/api/events/' + id);

        return await event.json();
    }

    function check(arr, key, value) {
        let r = 0;

        for (let i = 0; i < arr.length; i++) {
            if(arr[i][key] === value) {
                r++;
            }
        }

        return r !== 0;
    }


    document.addEventListener('DOMContentLoaded', async function() {
        var calendarEl = document.getElementById('calendar');

        let rooms = await fetch('http://localhost/api/rooms');
        let json = await rooms.json();

        let events = await fetch('http://localhost/api/events');
        let json2 = await events.json();

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'UTC',
            initialView: 'resourceTimeGridDay',
            resources: json,
            dayMinWidth: 150,
            eventColor: '#ffa44f',
            stickyFooterScrollbar : true,
            events: json2['data'],
            editable: !`{{ Auth::user()->status == 0}}`,
            selectable: !`{{ Auth::user()->status == 0}}`,
            slotDuration: '00:15:00',
            eventContent: function(arg) {

                if(`{{ Auth::user()->status == 0}}`) {

                    let btn = document.createElement('button');
                    btn.setAttribute('style', 'font-size: 20px;');

                    let p = document.createElement('p');
                    p.setAttribute('style', 'font-size: 30px; margin-left: 20px;')
                    p.innerText = arg.timeText;

                    btn.innerHTML = check(arg.event._def.extendedProps.users, 'name', `{{Auth::user()->name}}`) ? 'unjoin' : 'join'

                    btn.onclick = async function () {

                        let id = `{{ Auth::user()->id }}`;

                        if(btn.innerHTML === 'join') {
                            let join = await fetch(`http://localhost/api/join`, {
                                method: "POST",
                                mode: "cors",
                                cache: "no-cache",
                                credentials: "same-origin",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                redirect: "follow",
                                referrerPolicy: "no-referrer",
                                body: JSON.stringify({user_id: id, event_id: arg.event._def.publicId})
                            });
                        } else {
                            let unjoin = await fetch(`http://localhost/api/unjoin`, {
                                method: "POST",
                                mode: "cors",
                                cache: "no-cache",
                                credentials: "same-origin",
                                headers: {
                                    "Content-Type": "application/json",
                                },
                                redirect: "follow",
                                referrerPolicy: "no-referrer",
                                body: JSON.stringify({user_id: id, event_id: arg.event._def.publicId})
                            });
                        }

                        location.reload()

                    }

                    return { domNodes: [p, btn] }
                } else {

                    let ul = document.createElement('ul');
                    ul.setAttribute('style', 'font-size: 20px; color: black;');

                    let p = document.createElement('p');
                    p.setAttribute('style', 'color: black; font-size: 15px; margin-left: 20px; margin-top: 10px')
                    p.innerText = arg.timeText;

                    let p2 = document.createElement('p');
                    p2.setAttribute('style', 'color: black; font-size: 30px; margin-left: 20px;');
                    p2.innerText = arg.event._def.extendedProps.format.toUpperCase();

                    let p3 = document.createElement('p');
                    p3.setAttribute('style', 'color: black; font-size: 20px; margin-left: 20px;');
                    p3.innerText = arg.event._def.title;

                    fetch('http://localhost/api/events/' + arg.event.id)
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            } else {
                                throw new Error('Failed to fetch event content');
                            }
                        })
                        .then(content => {

                            content['data']['users'].forEach((item) => {
                                ul.innerHTML += `<li style="margin-bottom: 5px; padding: 2px; list-style: none; width: 300px; background: white; border-radius: 10px">${ item.name }</li>`
                            });

                        })
                        .catch(error => {
                            console.error('Error fetching event content:', error);
                        });

                    return { domNodes: [p, p2, p3, ul] }

                }


            },

            eventDrop: async function (info) {
                await fetch(`http://localhost/api/events/${info.event.id}`, {
                    method: "PUT",
                    mode: "cors",
                    cache: "no-cache",
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    redirect: "follow",
                    referrerPolicy: "no-referrer",
                    body: JSON.stringify({start: info.event.start, end: info.event.end, resourceId: info.event._def.resourceIds[0]})
                });
            }
        });

        calendar.render();

        if(`{{ Auth::user()->status == 1 }}`) {
            let elems = document.querySelectorAll('.fc-col-header-cell-cushion');

            for(let i = 0; i < elems.length; i++) {
                elems[i].innerHTML = `<br/><button onclick="showModal(${json[i].id})" data-bs-toggle="modal" data-bs-target="#exampleModal"  class="btn btn-success"><i class="fa-solid fa-plus"></i></button> <button onclick="deleteRoom(${json[i].id})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button><br>` + json[i].title;
            }

            let primary = document.querySelectorAll('.fc-button-primary');

            for(let i = 0; i < primary.length; i++) {
                primary[i].onclick = function () {
                    let elems = document.querySelectorAll('.fc-col-header-cell-cushion');

                    for(let i = 0; i < elems.length; i++) {
                        elems[i].innerHTML = ''
                        elems[i].innerHTML = `<br/><button onclick="showModal(${json[i].id})" data-bs-toggle="modal" data-bs-target="#exampleModal"  class="btn btn-success"><i class="fa-solid fa-plus"></i></button> <button onclick="deleteRoom(${json[i].id})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button><br>` + json[i].title;
                    }
                }
            }
        }

    });

    document.getElementById('create_room').onclick = async function () {
        await fetch('http://localhost/api/rooms', {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
            },
            redirect: "follow",
            referrerPolicy: "no-referrer",

        });
        location.reload();
    }

    async function showModal(id) {
        document.getElementById('submit').setAttribute('onclick', `create(${id})`)
    }

    async function create(a) {

        let title = document.getElementById('title').value;
        let format = document.getElementById('format').value;
        let start = document.getElementById('start').value;
        let end = document.getElementById('end').value;
        let users = document.getElementById('users');

        let arr = []

        for (elem of users.options) {
            if(elem.selected) {
                arr.push(elem.value.replaceAll('-', ' '))
            }
        }
        console.log(JSON.stringify({start: start, end: end, format2: format, title: title, resource_id: a, users: arr}))

        await fetch('http://localhost/api/events', {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            credentials: "same-origin",
            headers: {
                "Content-Type": "application/json",
            },
            redirect: "follow",
            referrerPolicy: "no-referrer",
            body: JSON.stringify({start: start, end: end, format2: format, title: title, resource_id: a, users: arr})
        });

        location.reload()

    }

    if(`{{ Auth::user()->status == 1 }}`) {
        let format = document.getElementById('format');

        format.onchange = function () {
            if(format.value === 'group') {
                document.getElementById('users').setAttribute('multiple', '')
            } else if(format.value === 'ind') {
                document.getElementById('users').removeAttribute('multiple')
            }
        }

    }

</script>
</body>
</html>

