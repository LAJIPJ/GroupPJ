$(document).ready(function() {
    $('select').material_select();
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 1 // Creates a dropdown of 15 years to control year
    });
    var $input = $('.datepicker').pickadate()

// Use the picker object directly.
    var picker = $input.pickadate('picker')
    // Using arrays formatted as [YEAR, MONTH, DATE].
    picker.set('select', joinDate, { format: 'yyyy-mm-dd' })

    console.log(isEditing)
    if (!isEditing) {
        // $('#info_form :input').val("")
    }

    $("#submitButton").click(function (event) {
        event.preventDefault()
        var $inputs = $('#info_form :input');

        // not sure if you wanted this, but I thought I'd add it.
        // get an associative array of just the values.
        var values = {};
        $inputs.each(function() {
            values[this.name] = $(this).val();
        });

        console.log(values);
        if (isEditing) {

        } else {
            $.post(addEndPoint, values, function (data) {
                console.log(data);
            })
        }
    })
});