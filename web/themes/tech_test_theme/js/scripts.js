jQuery(document).ready(function () {
  if (jQuery('#article_weather').length) {
    const postcode = jQuery('#article_weather').text();
    get_weather(postcode);
  }
});

function get_weather(postcode){
  const weather_div = jQuery('#article_weather');
  const weather = jQuery.getJSON( '/api/fetch-weather/' + postcode, function ( data ) {
    weather_div.html('The weather in this area today is: ' + data['weather']);
    weather_div.removeClass('hidden');
    setTimeout(function () {
            get_weather(postcode);
          }, 10000);
  });
}
