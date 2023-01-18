@php
    $tops = [301, 351, 400, 449, 499];
@endphp

@for($rowIndex = 0; $rowIndex < 5; $rowIndex++)
    <x-last-chaos::character-inventory-button
        :row="$rowIndex + (6 * $this->tab)"
        :column="0"
        :height="45"
        :width="45"
        :top="$tops[$rowIndex]"
        :left="54"
    />

    <x-last-chaos::character-inventory-button
        :row="$rowIndex + (6 * $this->tab)"
        :column="1"
        :height="46"
        :width="46"
        :top="$tops[$rowIndex]"
        :left="103"
    />

    <x-last-chaos::character-inventory-button
        :row="$rowIndex + (6 * $this->tab)"
        :column="2"
        :height="46"
        :width="45"
        :top="$tops[$rowIndex]"
        :left="153"
    />

    <x-last-chaos::character-inventory-button
        :row="$rowIndex + (6 * $this->tab)"
        :column="3"
        :height="46"
        :width="46"
        :top="$tops[$rowIndex]"
        :left="202"
    />

    <x-last-chaos::character-inventory-button
        :row="$rowIndex + (6 * $this->tab)"
        :column="4"
        :height="45"
        :width="45"
        :top="$tops[$rowIndex]"
        :left="252"
    />
@endfor
