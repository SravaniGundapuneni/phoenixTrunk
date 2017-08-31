/*jshint browser: true*/
/*globals window*/

(function ($) {
  $('#selectForm').one("click", function() {
   
    if ($(this).val() == 'select') {
      //create three initial fields
      var startingNo = 0;
      var $node = "";

      for (varCount = 0; varCount <= startingNo; varCount++) {
        var displayCount = varCount + 1;
        $node += '<p><label class="blockLabel" for="selectValue' + displayCount + '">Value: ' + displayCount + '</label><input type="text" id="addgdh" name="selectValue' + displayCount + '" id="selectValue' + displayCount + '"><span class="removeDepartment">Remove</span><span id="addDepartment">Add</span></p>';
      }

      //add them to the DOM
      $('form#dynamicListModuleField').append($node);

      //remove a textfield   
      $('form#dynamicListModuleField').on('click', '.removeDepartment', function() {
        $(this).parent().remove();
      });

      //add a new node
      $('#addDepartment').on('click', function() {
        varCount++;
        $node = '<p><label class="blockLabel" for="selectValue' + varCount + '">Value:' + varCount + ' </label><input type="text"  name="selectValue' + varCount + '" id="selectValue' + varCount + '"><span class="removeDepartment">Remove</span></p>';
        $(this).parent().after($node);
      });
    }
  });
  
   $('#selectForm').one("click", function() {
   
    if ($(this).val() == 'select') {
      //create three initial fields
      var startingNo = 0;
      var $node = "";

      for (varCount = 0; varCount <= startingNo; varCount++) {
        var displayCount = varCount + 1;
        $node += '<p><label class="blockLabel" for="selectValue' + displayCount + '">Value: ' + displayCount + '</label><input type="text" id="addgdh" name="selectValue' + displayCount + '" id="selectValue' + displayCount + '"><span class="removeDepartment">Remove</span><span id="addDepartment">Add</span></p>';
      }

      //add them to the DOM
      $('form#flexibleFormsField').append($node);

      //remove a textfield   
      $('form#flexibleFormsField').on('click', '.removeDepartment', function() {
        $(this).parent().remove();
      });

      //add a new node
      $('#addDepartment').on('click', function() {
        varCount++;
        $node = '<p><label class="blockLabel" for="selectValue' + varCount + '">Value:' + varCount + ' </label><input type="text"  name="selectValue' + varCount + '" id="selectValue' + varCount + '"><span class="removeDepartment">Remove</span></p>';
        $(this).parent().after($node);
      });
    }
  });
  
  $(function () {
    var datepickerSelectors = [
        'input[name="startDate"]',
        'input[name="autoExpire"]',
        'input[name="autoExpiry"]',
        'input[name="eventStart"]',
        'input[name="eventEnd"]',
        'input[type="date"]'
      ],
      datepickerOptions = {
        dateFormat: 'yy-mm-dd',
      };

    $(datepickerSelectors.join(', ')).datepicker(datepickerOptions);
  });
}(window.jQuery));
