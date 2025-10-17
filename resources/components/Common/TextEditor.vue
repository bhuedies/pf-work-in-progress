<template>
    <div ref="editor"></div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const editor = ref(null);
let quill = null;

onMounted(async () => {
    if (typeof window !== "undefined") {
        const Quill = (await import("quill")).default;
        import("quill/dist/quill.snow.css");

        if (editor.value) {
            quill = new Quill(editor.value, {
                theme: "snow",
                placeholder: "Insert text here ...",
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ["bold", "italic", "underline"],
                        [{ list: "ordered" }, { list: "bullet" }],
                        ["link"],
                    ],
                },
            });
            editor.value.style.height = "150px";
        }
    }
});

onBeforeUnmount(() => {
    if (quill) {
        quill = null;
    }
});
</script>
