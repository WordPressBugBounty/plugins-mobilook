let wasSavingPost = false

wp.data.subscribe(() => {
  const isSavingPost = wp.data.select("core/editor").isSavingPost()

  if (wasSavingPost && !isSavingPost) {
    console.log("Post has been saved. Update Mobilook preview")
    const iframe = document.querySelector("iframe#mobilook-iframe")
    if (iframe) {
      iframe.contentWindow.location.reload()
    }
  }

  wasSavingPost = isSavingPost
})
