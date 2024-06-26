@if(session('error'))
    <div class="alert alert-danger">
        <div>
            <div class="d-flex">
                <i class="fa-solid fa-face-frown me-5"></i>
                <span class="mt-1">{{ session('error') }}</span>
            </div>
        </div>
    </div>
@endif
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" id="success-message">
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  <h4><i class="icon fa fa-check"></i> Success!</h4>
{{ session('success') }}
</div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to fade out the message after a certain duration
        function fadeOutMessage(messageId) {
            var message = document.getElementById(messageId);
            if (message) {
                setTimeout(function() {
                    message.style.transition = "opacity 1s";
                    message.style.opacity = "0";
                    setTimeout(function() {
                        message.remove();
                    }, 1000); // Delay for 1 second after fading out
                }, 3000); // 3000 milliseconds = 3 seconds
            }
        }

        // Call the function for error and success messages
        fadeOutMessage('error-message');
        fadeOutMessage('success-message');
    });
</script>