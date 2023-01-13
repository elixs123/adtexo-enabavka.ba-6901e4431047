// Pusher: Connected
function pusher_connected() {
    // Check
    if (typeof auth_hash === "undefined") {
        // Return
        return;
    }
    // Event: Connected
    pusher.connection.bind('connected', function() {
        // Socket id
        socket_id = pusher.connection.socket_id;
        // Ajax: Socket id
        $.ajaxSetup({
            headers: {
                'X-Socket-ID': socket_id
            }
        });
    });
}

// Presence
function pusher_presence() {
    // Check
    if (typeof auth_hash === "undefined") {
        // Return
        return;
    }
    var channel = pusher.subscribe('presence-auth');
    channel.bind('pusher:subscription_succeeded', function(members) {
        // Pusher: Me
        pusher_me = channel.members.me;
        // Update member count
        update_member_count(members.count - 1);
        // Members list
        members.each(function (member) {
            // Check: Me
            if (parseInt(member.id) !== pusher_me.id) {
                // Add member
                add_member(member, 'subscription');
            }
        });
    });
    channel.bind('pusher:subscription_error', function(status) {
        console.error(status);
    });
    channel.bind('pusher:member_added', function(member) {
        // Add member
        add_member(member, 'member_added');
        // Update member count
        update_member_count($('[data-member]').length);
    });
    channel.bind('pusher:member_removed', function(member) {
        // Remove member
        $('[data-member="' + member.id + '"]').remove();
        // Update member count
        update_member_count($('[data-member]').length);
    });
}
function update_member_count(count) {
    $('[data-members][data-num]').attr('data-num', count);
    $('[data-members-num]').text(count);
}
function add_member(member, type) {
    // Check
    if ($('[data-member="' + member.id + '"]').length > 0) {
        return;
    }
    // Html
    var html = [
        '<div data-member="' + member.id + '" class="notification-item clearfix">',
            '<div class="heading">',
                '<div class="thumbnail-wrapper d24 circular b-white m-r-5 b-white m-t-10 m-r-10">',
                    '<img width="30" height="30" src="' + member.info.avatar + '">',
                '</div>',
                '<a href="javascript:;" class="pull-left">',
                    '<span class="bold">' + member.info.name + '</span>',
                    '<span class="fs-12 m-l-10">' + member.info.role + '</span>',
                '</a>',
            '</div>',
        '</div>'
    ];
    // Prepend
    $('[data-members-content]').prepend(html.join(''));
}

// Document ready
$(function() {
    // Pusher
    pusher_connected();
    pusher_presence();
});