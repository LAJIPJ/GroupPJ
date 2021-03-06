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
        $('#info_form :input').val("")
    }

    if (shouldDisable) {
        $('#info_form :input').attr('disabled', true)
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
            values["staffId"] = staffId
            $.post(editEndPoint, values, function (data) {
                if (data) {
                    alert("修改成功!")
                } else {
                    alert("修改失败!")
                }
            })
        } else {
            $.post(addEndPoint, values, function (data) {
                if (data) {
                    alert("添加成功!")
                    $('#info_form :input').val("")
                } else {
                    alert("添加失败!")
                }
            })
        }

    })
});