$('#header .top .rightArea .search .icon').click(function(){
	$(this).parent('.search').toggleClass('formOpened');
});


$('#header .top .rightArea .search .icon, #header .top .rightArea .search form, .dropdownTitle').click(function(e){
	e.stopPropagation();
});

$('.dropdownTitle').click(function(){
	$('.dropdownContent').css('display', 'none');
	$(this).next('.dropdownContent').stop().slideToggle();
});

$('#header .bottom #nav .icon').click(function(){
	$('.mobileMenu').fadeToggle();
});


$(document).click(function(){
	$('#header .search').removeClass('formOpened');
	$('.dropdownContent').css('display', 'none');
});

$('.openPopup').click(function(){
	var theUrl = $(this).attr('data-link');
	$('body').append('<div class="thePopup"><div class="closePopup"></div><iframe src="' + theUrl + '" frameborder="0"></iframe></div>')
});

$('body').on('click', '.closePopup', function(){
	$('.thePopup').remove();
});

function readURL(ele, input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.' + ele).attr('src', e.target.result).prev('.hideAfterUpload').addClass('disNone');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$('.importItemsForm input[type="file"]').change(function(){
	var theFileUrl = $(this).val();
	$(this).next('.uploadedFileDisplay').html(theFileUrl);
});