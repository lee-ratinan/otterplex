$(document).ready(function() {
    // Map your custom database types to specific FontAwesome classes
    const iconMapping = {
        'new_order': 'fa-solid fa-bag-shopping text-success',
        'default': 'fa-solid fa-bell text-secondary'
    };
    function loadNotifications() {
        $.ajax({
            url: '/admin/notifications/fetch',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // 1. Manage Badge Counter
                if (data.unread_count > 0) {
                    let unread = data.unread_count;
                    if (99 < unread) {
                        unread = '99+';
                    }
                    $('#noti-badge').text(unread).removeClass('d-none');
                } else {
                    $('#noti-badge').addClass('d-none');
                }
                // 2. Build List
                let htmlOutput = '';
                if (data.notifications.length === 0) {
                    htmlOutput = `<li class="notification-item"><i class="fa-solid fa-triangle-exclamation"></i><div><p>${str_no_notifications}</p></div></li>`;
                } else {
                    data.notifications.forEach(function(item) {
                        let iconClass = iconMapping[item.notification_type] || iconMapping['default'];
                        let unreadClass = (item.is_read === 'Y' ? '' : 'fw-bold');
                        // Parse standard DB UTC to local readable context
                        let localTime = new Date(item.created_at + ' UTC').toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        htmlOutput += `<li class="notification-item"><i class="${iconClass}"></i><div><a href="${item.notification_link}" class="noti-item" data-id="${item.id}"><p class="${unreadClass}">${item.notification_message}</p><p>${localTime}</p></a></div></li><li><hr class="dropdown-divider"></li>`;
                    });
                }
                htmlOutput += `<li class="dropdown-footer"><a href="/admin/notifications">${str_show_all_notification}</a></li>`
                $('#noti-items-container').html(htmlOutput);
            }
        });
    }
    // Periodically fetch notifications every 120 seconds (Long-polling alternative)
    loadNotifications();
    setInterval(loadNotifications, 120000);
    // Event Delegation: When clicking an item, mark read in background then proceed to link
    $(document).on('click', '.noti-item', function(e) {
        e.preventDefault();
        let element = $(this);
        let notiId = element.data('id');
        let destinationUrl = element.attr('href');
        // Execute background post request to update state
        $.ajax({
            url: `/admin/notifications/mark-read/${notiId}`,
            method: 'POST',
            success: function() {
                // Navigate seamlessly to target link after marking read
                window.location.href = destinationUrl;
            },
            error: function() {
                // Fail-safe redirect if backend route breaks
                window.location.href = destinationUrl;
            }
        });
    });
});