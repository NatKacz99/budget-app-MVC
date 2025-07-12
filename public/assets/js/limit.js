const getLimitForCategory = async (categoryName) => {
  try {
    const res = await fetch(`/api/limit?category=${categoryName}`);
    const data = await res.json();
    return data;
  } catch (e) {
    console.log('ERROR', e);
    return null;
  }
}