        <table>
          <tbody>
            <tr>
              <td colspan="2" class="itemfirstcol"><strong>Custom Meta:</strong><br>
                <a href="#" class="add_more_meta" onClick="return add_more_meta(this)"> + Add Custom Meta </a><br>
                <br>
                <div class="product_custom_meta"> Name: <br>
                  <input type="text" name="new_custom_meta[name][]" value="" class="text">
                  <br>
                  Description: <br>
                  <textarea name="new_custom_meta[value][]" cols="40" rows="10" class="text"></textarea>
                  <br>
                </div></td>
            </tr>
            <tr>
              <td class="itemfirstcol" colspan="2"><br>
                <strong>Merchant Notes:</strong><br>
                <textarea cols="40" rows="3" name="meta[_wpsc_product_metadata][merchant_notes]" id="merchant_notes"></textarea>
                <small>These notes are only available here.</small></td>
            </tr>
            <tr>
              <td class="itemfirstcol" colspan="2"><br>
                <strong>Personalisation Options:</strong><br>
                <input type="hidden" name="meta[_wpsc_product_metadata][engraved]" value="0">
                <input type="checkbox" name="meta[_wpsc_product_metadata][engraved]" id="add_engrave_text">
                <label for="add_engrave_text">Users can personalize this Product by leaving a message on single product page</label>
                <br></td>
            </tr>
            <tr>
              <td class="itemfirstcol" colspan="2"><input type="hidden" name="meta[_wpsc_product_metadata][can_have_uploaded_image]" value="0">
                <input type="checkbox" name="meta[_wpsc_product_metadata][can_have_uploaded_image]" id="can_have_uploaded_image">
                <label for="can_have_uploaded_image"> Users can upload images on single product page to purchase logs. </label>
                <br></td>
            </tr>
            <tr>
              <td class="itemfirstcol" colspan="2"><br>
                <strong>Enable Comments:</strong><br>
                <select name="meta[_wpsc_product_metadata][enable_comments]">
                  <option value="" selected="">Use Default</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
                <br>
                Allow users to comment on this Product. </td>
            </tr>
          </tbody>
        </table>