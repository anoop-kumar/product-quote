<a name="wpsc_shipping"></a>
        <table>
          
          <!--USPS shipping changes-->
          <tbody>
            <tr>
              <td> Weight </td>
              <td><input type="text" size="5" name="meta[_wpsc_product_metadata][weight]" value="0">
                <select name="meta[_wpsc_product_metadata][weight_unit]">
                  <option value="pound" selected="selected">Pounds</option>
                  <option value="ounce">Ounces</option>
                  <option value="gram">Grams</option>
                  <option value="kilogram">Kilograms</option>
                </select></td>
            </tr>
            <!--dimension-->
            <tr>
              <td> Height </td>
              <td><input type="text" size="5" name="meta[_wpsc_product_metadata][dimensions][height]" value="0">
                <select name="meta[_wpsc_product_metadata][dimensions][height_unit]">
                  <option value="in" selected="">inches</option>
                  <option value="cm">cm</option>
                  <option value="meter">meter</option>
                </select></td>
            </tr>
            <tr>
              <td> Width </td>
              <td><input type="text" size="5" name="meta[_wpsc_product_metadata][dimensions][width]" value="0  ">
                <select name="meta[_wpsc_product_metadata][dimensions][width_unit]">
                  <option value="in" selected="">inches</option>
                  <option value="cm">cm</option>
                  <option value="meter">meter</option>
                </select></td>
            </tr>
            <tr>
              <td> Length </td>
              <td><input type="text" size="5" name="meta[_wpsc_product_metadata][dimensions][length]" value="0">
                <select name="meta[_wpsc_product_metadata][dimensions][length_unit]">
                  <option value="in" selected="">inches</option>
                  <option value="cm">cm</option>
                  <option value="meter">meter</option>
                </select></td>
            </tr>
            
            <!--//dimension--> 
            <!--USPS shipping changes ends-->
            <tr>
              <td colspan="2"><strong>Flat Rate Settings</strong></td>
            </tr>
            <tr>
              <td> Local Shipping Fee </td>
              <td><input type="text" size="10" name="meta[_wpsc_product_metadata][shipping][local]" value="0.00"></td>
            </tr>
            <tr>
              <td> International Shipping Fee </td>
              <td><input type="text" size="10" name="meta[_wpsc_product_metadata][shipping][international]" value="0.00"></td>
            </tr>
            <tr>
              <td><br>
                <input id="add_form_no_shipping" type="checkbox" name="meta[_wpsc_product_metadata][no_shipping]" value="1">
                &nbsp;
                <label for="add_form_no_shipping">Disregard Shipping for this Product</label></td>
            </tr>
          </tbody>
        </table>
     