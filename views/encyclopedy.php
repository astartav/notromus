<div id="monitor">
<h2>Энциклопедия</h2>
        <form>
        <input name='topic' id='topic' type='hidden' value='' />
        <input name='chapter' id='chapter' type='hidden' value='' />
        <input name='page' id='page' type='hidden' value='' />
        <select id='topics' size='10' onchange="this.form.topic.value=options[this.selectedIndex].value;sendw('navigation','topic;chapter;page');" >
            <option value='fractions'>Фракции</option>
            <option value='navigation'>Навигация</option>
            <option value='ship'>Корабли</option>
            <option value='locations'>Локации</option>
        </select>
        <br />
        <input name='newtopic' id='newtopic' type='text' />
        <input type='button' value='new topic' onclick="sendw('newtopic','topic;chapter;page');" />
        </form>
</div>