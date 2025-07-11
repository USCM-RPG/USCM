<h1 class="heading heading-h1">About</h1>

<section>
  <h2 class="heading heading-h2">USCM Roleplaying game</h2>

  <section lang="sv">
    <h3 class="heading heading-h3">Swedish info</h3>

    <p>
      Det här är en rollspelskampanj i militär science fiction-miljö inspirerad främst av Alien-filmerna,
      men även Starship Troopers, XCOM m.m. Spelarnas karaktärer är soldater i framtidens motsvarighet till
      den amerikanska marinkåren. Stämningen i kampanjen inriktas i första hand på action, spänning och
      taktisk planering, men det kan vara stor variation mellan olika uppdrag.
    </p>

    <p>
      Kampanjen har ingen fast spelargrupp. Den är istället organiserad på ett sätt som låter fler spelare
      vara med i den. Varje uppdrag är ett fristående scenario. Alla spelare är med på en maillista och när
      en spelledare har gjort ett nytt äventyr skickas ett "announcement" för detta ut på maillistan. Detta
      innehåller tid och plats, antal spelare samt en kort beskrivning av uppdraget. Sedan är det först till
      kvarn. De första spelarna som anmäler sig får en mer detaljerad briefing som bekräftelse på att de har
      kommit med på uppdraget.<br>

      Det gör att det blir variation i spelgruppen från gång till gång och det går bra att vara med i kampanjen
      även om man inte kan spela så ofta.
    </p>

    <p>
      Alla uppdrag som spelats inom kampanjen finns beskrivna under "Missions" med den briefing-text som spelarna
      fick ut innan uppdraget och med sammanfattningen som skrivits efteråt av spelledaren eller någon spelare.
      Scenarion vi kör på konvent brukar inte få egna debriefingar, men dom spelas ofta som ett kampanjmission
      också.
    </p>

    <p>
      Kampanjen håller mestadels till i Linköping där den startade. Det har periodvis även funnits aktiva
      spelgrupper (plutoner) på andra orter och på senare tid kör vi ibland missions över mail också. USCM brukar
      även dyka upp som arrangemang på spelkonventet <a href="http://www.lincon.se" target="_blank">LinCon</a>.
    </p>

    <p>
      Det finns för tillfället plats för fler spelare, kom in på vår
      <a href="https://discord.gg/nEp7kwd4h7" target="_blank">Discord</a> om du är intresserad.
    </p>
  </section>

  <section>
    <h3 class="heading heading-h3">English info</h3>

    <p>This is the homepage for a roleplaying game set in a universe inspired mainly by the Alien movies.</p>

    <p>
      The goal of this campaign is to mix the atmosphere of the Alien movies with a military-style RPG campaign
      where we focus on action and tactical planning.
    </p>

    <p>
      Most roleplaying sessions are done in the traditional tabletop style, but there are occasional PBM or online
      scenarios as well.
    </p>
  </section>
</section>

<hr class="line">

<section>
<h2 class="heading heading-h2">Resources</h2>

<h3 class="heading heading-h3">Campaign material</h3>
<a href="assets/files/EquipmentListPrint2.pdf" target="_blank">Equipment list</a> (PDF) Overview for printing<br/>
<a href="assets/files/EquipmentBookletPrint.pdf" target="_blank">Reference Booklet</a> (PDF) USCM background info and equipment details.<br/>
<a href="assets/files/v3/USCM-Rules2-cm.pdf" target="_blank">Campaign world Reference, USCM edition - Skills/Traits/Expertise etc</a> (PDF)<br/>
Campaign world Reference, UPP edition - Skills/Traits/Expertise etc</a> (Coming..)<br/>
<a href="assets/files/v3/USCM-Rules2-complete.pdf" target="_blank">Campaign world Reference, Complete edition</a> (PDF) You typically won't need this, pick the slimmed version for your campaign instead<br/>
<br/>
<a href="assets/files/InofficiellBriefingBF5.pdf" target="_blank">Unofficial briefing</a> (PDF) BF5s knowledge about aliens, in swedish<br/>


<h3 class="heading heading-h3">
  USCM v3 <span class="tag tag-bf5">current version</span>
</h3>
<a href="assets/files/v3/USCM-RulesSummary.pdf" target="_blank">One page rules summary</a> (PDF)<br/>
<a href="assets/files/v3/CharacterSheet.ods" target="_blank">Simple character sheet for manual character generation</a> (OpenDocument)<br/>
<a href="https://github.com/USCM-RPG/character-generator" target="_blank">Character Generator</a> (github project)<br/>

<br/>
<h3 class="heading heading-h3">
  USCM v2 <span class="tag">old version</span>
</h3>

<a href="assets/files/v2/charactersheet.pdf" target="_blank">Character Sheet</a> (PDF)<br/>
<a href="assets/files/v2/USCM-Rules2.pdf" target="_blank">Rules - Properties/Cert</a> (PDF)<br/><br/>

<a href="assets/files/v2/USCM_Generator.ods" target="_blank">Character Generator</a> (OpenDocument - LibreOffice)<br/>
To use the old Character Generator you need <a href="http://www.libreoffice.org" target="_blank">LibreOffice</a> (free, open source).<br/>
You need to activate macros under Tools->Options->LibreOffice->Security. A good way to do it is to set the folder with the Character generator in it as a "Trusted Source".<br/><br/>
</section>

<hr class="line">

<section>
  <h2 class="heading heading-h2">Credits</h2>

  <p>Created by <a href="mailto:oskar.grindemyr@gmail.com?subject=USCM">Oskar</a></p>

  <p>
    Campaign missions by<br>
    <?php
    $sql = "select forname, lastname from GMs left join Users on GMs.userid = Users.id;";
    $dbReference = getDatabaseConnection();
    $stmt = $dbReference->query($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo $row['forname'] .' '. $row['lastname'] .', ';
    }
    ?>
  </p>

  <p>
    Website by<br>
    <a href="https://github.com/uscm-rpg/uscm/graphs/contributors">
      <img src="https://contrib.rocks/image?repo=uscm-rpg/uscm" alt="Website creators" />
    </a>
  </p>

  <p>
    Character generator by<br>
    <a href="https://github.com/uscm-rpg/character-generator/graphs/contributors">
      <img src="https://contrib.rocks/image?repo=uscm-rpg/character-generator" alt="Character generator creators" />
    </a>
  </p>
</section>
