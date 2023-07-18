<fieldset class="rating">
    <input type="radio" {{ $data->rating == 5 ? ' checked' : ''  }} id="star5" name="rating" value="5" />
    <label class = "full" for="star5" title="Awesome - 5 stars"></label>
    <input type="radio" {{ $data->rating == 4.5 ? ' checked' : ''  }} id="star4half" name="rating" value="4.5" />
    <label class="half" for="star4half" title="Pretty good - 4.5 stars"> </label>
    <input type="radio" {{ $data->rating == 4 ? ' checked' : '' }} id="star4" name="rating" value="4" />
    <label class = "full" for="star4" title="Pretty good - 4 stars"></label>
    <input type="radio" {{ $data->rating == 3.5 ? ' checked' : '' }} id="star3half" name="rating" value="3.5" />
    <label class="half" for="star3half" title="Meh - 3.5 stars"></label>
    <input type="radio" {{ $data->rating == 3 ? ' checked' : '' }} id="star3" name="rating" value="3" />
    <label class = "full" for="star3" title="Meh - 3 stars"></label>
    <input type="radio" {{ $data->rating == 2.5 ? ' checked' : '' }} id="star2half" name="rating" value="2.5" />
    <label class="half" for="star2half" title="Kinda bad - 2.5 stars"></label>
    <input type="radio"{{  $data->rating == 2 ? ' checked' : '' }} id="star2" name="rating" value="2" />
    <label class = "full" for="star2" title="Kinda bad - 2 stars"></label>
    <input type="radio" {{ $data->rating == 1.5 ? ' checked' : '' }} id="star1half" name="rating" value="1.5" />
    <label class="half" for="star1half" title="Meh - 1.5 stars"></label>
    <input type="radio" {{ $data->rating == 1 ? ' checked' : '' }} id="star1" name="rating" value="1" />
    <label class = "full" for="star1" title="bad time - 1 star"></label>
    
</fieldset>
