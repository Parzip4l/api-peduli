<header class="topbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <button type="button" class="button-toggle-menu me-2">
                        <iconify-icon icon="solar:hamburger-menu-broken" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <!-- Menu Toggle Button -->
                <div class="topbar-item">
                    <h4 class="fw-bold topbar-button pe-none text-uppercase mb-0">{{ $title ?? 'Peduli APP LRTJ' }}</h4>
                </div>
            </div>

            <div class="d-flex align-items-center gap-1">

                <!-- Theme Color (Light/Dark) -->
                <div class="topbar-item">
                    <button type="button" class="topbar-button" id="light-dark-mode">
                        <iconify-icon icon="solar:moon-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                    </button>
                </div>

                <!-- Notification -->
                <div class="dropdown topbar-item">
                    <button type="button" class="topbar-button position-relative"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <iconify-icon icon="solar:bell-bing-bold-duotone" class="fs-24 align-middle"></iconify-icon>
                        <span id="notif-count" class="position-absolute topbar-badge fs-10 translate-middle badge bg-danger rounded-pill">0
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </button>
                    <div class="dropdown-menu py-0 dropdown-lg dropdown-menu-end"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="m-0 fs-16 fw-semibold"> Notifications</h6>
                                </div>
                                <div class="col-auto">
                                    <a href="javascript: void(0);" id="clear-all-notif" class="text-dark text-decoration-underline">
                                        <small>Clear All</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div data-simplebar style="max-height: 280px;" id="notif-container">
                            <div class="text-center py-3 text-muted">Loading...</div>
                        </div>
                        <div class="text-center py-3">
                            <a href="/notifications/all" class="btn btn-primary btn-sm">View All Notification <i
                                    class="bx bx-right-arrow-alt ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- User -->
                <div class="dropdown topbar-item">
                    <a type="button" class="topbar-button" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                              <span class="d-flex align-items-center">
                                   <img class="rounded-circle" width="32" src="/images/users/avatar-1.jpg"
                                        alt="avatar-3">
                              </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header">{{ auth()->check() ? auth()->user()->name : 'Guest' }}!</h6>

                        <div class="dropdown-divider my-1"></div>

                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger me-1" style="border: none; background: none; padding: 0; margin-left:22px;">
                                <i class="bx bx-log-out fs-18 align-middle me-1"></i>
                                <span class="align-middle">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function loadNotifications() {
            fetch('/notifications')
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('notif-container');
                    const count = document.getElementById('notif-count');
                    container.innerHTML = '';

                    if (!data.notifications.length) {
                        container.innerHTML = `<div class="text-center py-3 text-muted">Tidak ada notifikasi</div>`;
                        count.textContent = '0';
                        return;
                    }

                    data.notifications.forEach(notif => {
                        const el = document.createElement('a');
                        el.href = notif.url || '#';
                        el.className = "dropdown-item py-3 border-bottom text-wrap";
                        el.setAttribute('data-id', notif.id);

                        el.addEventListener('click', function (e) {
                            // AJAX mark as read
                            fetch(`/notifications/read/${notif.id}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            }).then(() => loadNotifications());
                        });
                        
                        el.innerHTML = `
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="/images/users/avatar-1.jpg"
                                         class="img-fluid me-2 avatar-sm rounded-circle" alt="avatar" />
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 fw-semibold ${notif.is_read ? 'text-muted' : ''}">${notif.title}</p>
                                    <small class="text-muted">${notif.message}</small>
                                </div>
                            </div>
                        `;
                        container.appendChild(el);
                    });

                    count.textContent = data.unread_count;
                });
        }

        // Jalankan saat halaman selesai dimuat
        loadNotifications();

        // Refresh notifikasi setiap 60 detik
        setInterval(loadNotifications, 60000);

        // Tombol "Clear All"
        const clearAll = document.getElementById('clear-all-notif');
        if (clearAll) {
            clearAll.addEventListener('click', function () {
                fetch('/notifications/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => loadNotifications());
            });
        }
    });
</script>