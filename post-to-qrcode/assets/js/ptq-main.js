;(function ($) {
    $(document).ready(function () {
        var current_value = $("#ptq_toggle_field").val(); // Get the current value of the hidden input field
        $('#toggle1').minitoggle(); // Initialize the minitoggle

        // Check if the current value is '1' and set the toggle to active if it is
        if (current_value == 1) {
            $('#toggle1 .minitoggle').addClass('active');
        }

        // Add an event listener for the toggle event
        $('#toggle1').on('toggle', function (e) {
            if (e.isActive) {
                $("#ptq_toggle_field").val(1); // Set the hidden input field to '1' if the toggle is active
            } else {
                $("#ptq_toggle_field").val(0); // Set the hidden input field to '0' if the toggle is inactive
            }
        });
    });
})(jQuery);
