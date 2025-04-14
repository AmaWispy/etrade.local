<p>Redirecting...</p>
<form id="redirectForm" method="POST" action="{{$url}}">
@foreach($fields as $name => $value)
    <input type="hidden" name="{{$name}}" value="{{$value}}" />
@endforeach
<noscript>
<p>Seems that you have javascript disabled. Please click the button below to continue payment. You will be redirected to the gateway page.</p>
<button type="submit">Continue</button>
</noscript>
</form>
<script type="module">
// Function to submit the form when the page is ready
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('redirectForm');
    form.submit();
});
</script>