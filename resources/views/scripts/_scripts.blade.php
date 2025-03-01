<script>
    document.addEventListener('DOMContentLoaded', function() {
        const drawerToggle = document.getElementById('kt_activities_toggle');
        const timelineContainer = document.getElementById('kt_activities_timeline');

        function fetchLogs() {
            fetch("{{ route('logs.fetch') }}")
                .then(response => response.json())
                .then(data => {
                    timelineContainer.innerHTML = '';

                    if (data.logs.length > 0) {
                        data.logs.forEach(log => {
                            const timelineItem = document.createElement('div');
                            timelineItem.classList.add('timeline-item');
                            
                            timelineItem.innerHTML = `
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light">
                                        <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                                                <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                                                <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="timeline-content mt-n1">
                                    <div class="pe-3 mb-5">
                                        <div class="fs-5 fw-bold mb-2">${log.message}</div>
                                        <div class="text-gray-400 fw-semibold fs-7">${log.timestamp}</div>
                                    </div>
                                </div>
                            `;

                            timelineContainer.appendChild(timelineItem);
                        });
                    } else {
                        const noLogsMessage = document.createElement('div');
                        noLogsMessage.classList.add('text-center', 'text-muted', 'my-5');
                        noLogsMessage.textContent = 'No logs available';
                        timelineContainer.appendChild(noLogsMessage);
                    }
                })
                .catch(error => {
                    console.error('Error fetching logs:', error);
                });
        }

        drawerToggle.addEventListener('click', fetchLogs);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const drawerToggle = document.getElementById('kt_notifications_toggle');
        const timelineContainer = document.getElementById('kt_notifications_timeline');

        function fetchNotifications() {
            fetch("{{ route('notifications.fetch') }}")
                .then(response => response.json())
                .then(data => {
                    timelineContainer.innerHTML = '';

                    if (data.notifications.length > 0) {
                        data.notifications.forEach(notification => {
                            const timelineItem = document.createElement('div');
                            timelineItem.classList.add('timeline-item');
                            
                            timelineItem.innerHTML = `
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light">
                                        <span class="svg-icon svg-icon-2 svg-icon-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="timeline-content mt-n1">
                                    <div class="pe-3 mb-5">
                                        <div class="fs-5 fw-bold mb-2">${notification.message}</div>
                                        <div class="text-gray-400 fw-semibold fs-7">${notification.timestamp}</div>
                                    </div>
                                </div>
                            `;

                            timelineContainer.appendChild(timelineItem);
                        });
                    } else {
                        const noNotificationsMessage = document.createElement('div');
                        noNotificationsMessage.classList.add('text-center', 'text-muted', 'my-5');
                        noNotificationsMessage.textContent = 'No notifications available';
                        timelineContainer.appendChild(noNotificationsMessage);
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
        }

        drawerToggle.addEventListener('click', fetchNotifications);
    });

    // start typeahead
    $(document).ready(function(){
        var routes = <?php echo json_encode(Helper::get_route_names()); ?>;
        
        var routes = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            local: routes
        });
        
        $('#routes-search').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'routes',
            source: routes
        });

        $('#routes-search').on('typeahead:selected', function(event, suggestion, dataset) {
            $('#redirectForm input[name="route"]').val(suggestion);
            $('#redirectForm').submit();
        });
    });
    // end typeahead

// start todo 
document.addEventListener('DOMContentLoaded', function() {
    const drawerToggle = document.getElementById('kt_todos_toggle');
    const todoContainer = document.getElementById('todo_container');
    const inputBox = document.getElementById("todo_input");
    const form = document.getElementById("todo_form");

    function addTask() {
        const taskText = inputBox.value.trim();

        if (taskText === '') {
            alert("You must write something!");
            return;
        }

        fetch("{{ route('todos.create') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ text: taskText })
        })
        .then(response => response.json())
        .then(data => {
            let li = document.createElement("li");
            li.setAttribute('data-id', data.id);
            li.innerHTML = `
                ${data.text}
                <span class="remove-todo">x</span>
            `;
            if (data.status == 'completed') {
                li.classList.add('checked');
            }
            
            todoContainer.insertBefore(li, todoContainer.firstChild);

            inputBox.value = '';
        })
        .catch(error => console.error('Error:', error));
    }

    function removeTask(id, listItem) {
        const url = `{{ route('todos.destroy', '') }}/${id}`;
        fetch(url, {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => {
            if (response.ok) {
                listItem.remove();
            } else {
                console.error('Failed to delete task');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function toggleComplete(id, listItem) {
        const url = `{{ route('todos.complete', '') }}/${id}`;
        fetch(url, {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                listItem.classList.toggle('checked');
            } else {
                console.error('Failed to toggle completion');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function fetchTodos() {
        fetch("{{ route('todos.fetch') }}")
            .then(response => response.json())
            .then(data => {
                todoContainer.innerHTML = '';

                if (data.todos.length > 0) {
                    data.todos.forEach(todo => {
                        const li = document.createElement('li');
                        li.setAttribute('data-id', todo.id);
                        li.innerHTML = `
                            ${todo.text}
                            <span class="remove-todo">x</span>
                        `;
                        if (todo.status == 'completed') {
                            li.classList.add('checked');
                        }
                        todoContainer.insertBefore(li, todoContainer.firstChild);
                    });
                } else {
                    const noTodosMessage = document.createElement('div');
                    noTodosMessage.classList.add('text-center', 'text-muted', 'my-5');
                    noTodosMessage.textContent = 'No Todos available';
                    todoContainer.appendChild(noTodosMessage);
                }
            })
            .catch(error => {
                console.error('Error fetching todos:', error);
            });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        addTask();
    });

    todoContainer.addEventListener("click", function(e) {
        if (e.target.classList.contains("remove-todo")) {
            const todoId = e.target.parentElement.getAttribute('data-id');
            removeTask(todoId, e.target.parentElement);
        } else if (e.target.tagName === "LI") {
            const todoId = e.target.getAttribute('data-id');
            toggleComplete(todoId, e.target);
        }
    });

    drawerToggle.addEventListener('click', fetchTodos);
});
// end todo
</script>