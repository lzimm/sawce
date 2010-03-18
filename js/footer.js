/*$('.rc_tb').corner(); 
$('.rc_t').corner({br:{radius:0},bl:{radius:0}}); 
$('.rc_b').corner({tr:{radius:0},tl:{radius:0}});
$('.rc_r').corner({bl:{radius:0},tl:{radius:0}}); 
$('.rc_l').corner({br:{radius:0},tr:{radius:0}});
$('.rc_br').corner({tr:{radius:0},tl:{radius:0},bl:{radius:0}});*/

$('a').click(function(){this.blur();});

$('.song_playable').hover(function() { $(this).addClass('song_hover'); }, function() { $(this).removeClass('song_hover'); });
$('.song_playable').click(function() { $(this).find('a.play_btn').trigger('click'); });

$('.song_options').hover(function() { $(this).addClass('song_opt_over'); }, function() { $(this).removeClass('song_opt_over'); });
$('.song_options').click(function() { $(this).find('a.play_opt').trigger('click'); });

$('.play_btn').toggle(
    function() { 
        var song = this.id.replace('songbtn_','');
        var artist = this.rel;
        
        $('.song_playing').find('a.play_btn').trigger('click');
        
        $(this).find('span').text('Stop'); 
        $(this).parent().addClass('song_playing');
        
        play_track(artist, song);    
    },
    
    function() {
        var song = this.id.replace('songbtn_','');
        var artist = this.rel;
        
        $(this).find('span').text('Play');    
        $(this).parent().removeClass('song_playing');
        
        stop_track(song);
    }
);

$('.play_opt').toggle(
    function() {
        var song = this.id.replace('songopt_','');
        var artist = this.rel;
        
        $('.song_playing').find('a.play_opt').trigger('click');
        
        $(this).find('span').text('Stop');
        $(this).parent().addClass('song_playing');
        
        play_track(artist, song);    
    },
    
    function() {
        var song = this.id.replace('songopt_','');
        var artist = this.rel;
        
        $(this).find('span').text('Play');    
        $(this).parent().removeClass('song_playing');
        
        stop_track(song);
    }
);

var menuTimer = null;
var menuReset = null;
$('#nav_links a').hover(
    function() {
        clearTimeout(menuTimer);
        
        var button = '#' + this.id;
        var section = '#' + this.id + '_sub';

        $('#sub_nav div.aux_menu').hide();
        $('#sub_nav div.page_menu').hide();
        $('#lnk_' + $('body').attr('className')).addClass('off');
        $('#nav a.on').removeClass('on');
        
        $(section).show();
        $(button).addClass('on');
        
        menuReset = function() {
                $('#sub_nav div.page_menu').hide();   
                $('#auth_menu').show();  
                $('#lnk_' + $('body').attr('className')).removeClass('off');
                $(button).removeClass('on');                      
                
                $('#header').hover(function(){}, function(){});
            };
    },
    
    function() {
        menuTimer = setTimeout(menuReset, 1000);
    }
);

$('.page_menu').hover(
    function() {
        clearTimeout(menuTimer);
    },
    
    function() {
        menuTimer = setTimeout(menuReset, 500);
    }
);

function size_lb() {
    var needed = $('#lb_content').height() + 60;
    var have = $('#content').height();
    
    if (needed > have) {                         
        $('#content').css('height', have).animate({'height': needed + 'px'}, 200);
    }
}

function open_lb() {
    $('#lightbox').fadeIn(200);
    size_lb();
}

function close_lb() {
    $('#lightbox').fadeOut(500);
    $('#content').css('height', $('#content').height()).animate({'height': $('#darkbox').height() + 'px'}, 500); 
}

lb_links();
ipe_links();
frm_notes();