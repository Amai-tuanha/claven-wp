<svg class="svgCircle -step<?= $term_order ?>"
     xmlns="http://www.w3.org/2000/svg"
     viewBox="0 0 90 90.004">
  <!-- width="90"
     height="90.004" -->

  <g class="svgCircle__white"
     id="グループ_120"
     data-name="グループ 120"
     transform="translate(11296 17116.004)">
    <g id="楕円形_4"
       data-name="楕円形 4"
       transform="translate(-11296 -17116)"
       fill="none"
       stroke="#f2f2f2"
       stroke-width="13">
      <circle cx="45"
              cy="45"
              r="45"
              stroke="none" />
      <circle cx="45"
              cy="45"
              r="38.5"
              fill="none" />
    </g>

    <g class="svgCircle__colored"
       id="楕円形_5"
       data-name="楕円形 5"
       transform="translate(-11296 -17026.004) rotate(-90)"
       fill="none"
       stroke="<?= $stepColor ?>"
       stroke-linecap="round"
       stroke-width="13"
       style="stroke-dasharray:277; stroke-dashoffset:<?= $stepProgress_percetage ?>;">
      <circle cx="45"
              cy="45"
              r="45"
              stroke="none" />
      <circle cx="45"
              cy="45"
              r="38.5"
              fill="none" />
    </g>

    <g class="svgCircle__number"
       id="グループ_8"
       data-name="グループ 8"
       transform="translate(-12355 -17275)">
      <text class="svgCircle__numberParam"
            id="_7"
            data-name="7"
            transform="translate(1110 221)"
            fill="#707070"
            font-size="16"
            font-family="Helvetica"
            letter-spacing="0.05em">
        <tspan x="0"
               y="0"><?= $step_postCount ?></tspan>
      </text>
      <text class="svgCircle__numberCurrent"
            id="_5"
            data-name="5"
            transform="translate(1088 205)"
            fill="<?= $stepColor ?>"
            font-size="24"
            font-family="Helvetica"
            step-progress="<?= $stepProgress ?>"
            letter-spacing="0.05em">
        <tspan x="0"
               y="0"><?= $stepProgress ?></tspan>
      </text>
      <line id="線_2"
            data-name="線 2"
            x1="19"
            y2="21"
            transform="translate(1098.5 197)"
            fill="none"
            stroke="#707070"
            stroke-linecap="round"
            stroke-width="1" />
    </g>
  </g>
</svg>


<script>
$(function() {
  $('.svgCircle__numberCurrent').each(function() {
    var text = $(this).attr('step-progress');
    var textLength = text.length;
    var textLengthMove = (textLength - 1) * 10;
    $(this).attr('transform', 'translate(' + (1088 - textLengthMove) + ' 205)')

  })
});
</script>