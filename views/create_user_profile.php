<div id="monitor">
<h2>Персонаж - выбор персонажа</h2>
        <form>
        фракция:<br />
        <input name='fraction' id='fraction' type='hidden' value='' />
        <select size='3' onchange="this.form.fraction.value=this.selectedIndex;" >
            <option value='0'>Биониды</option>
            <option value='1'>Культ машин</option>
            <option value='2'>Инквизиторы</option>
        </select><br />
        имя персонажа:<br />
        <input name='name' id='name' type='text'/><br />
        описание:<br />
        <input name='description' id='description' type='text'/><br />
        <br />
        <input type='button' value='создать персонаж' onclick="sendw('addnew','name;fraction;description');" />
        </form>
</div>