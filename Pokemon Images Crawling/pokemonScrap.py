from bs4 import BeautifulSoup
import urllib.request
from selenium import webdriver
import time
import os

url = "https://www.pokemon.com/uk/pokedex/"
driver = webdriver.Chrome(os.path.dirname(os.path.abspath(__file__))+"/chromedriver.exe")
driver.maximize_window()
driver.get(url)
time.sleep(4)
source = driver.page_source
soup = BeautifulSoup(source, 'html.parser')
pokeTypeNum = 1

print("The Pokemon types in " + url + " are: ")
for pokeType in soup.findAll('span', class_='filter type-filter toggle type'):
    print("%d. %s" % (pokeTypeNum, pokeType["data-value"]))
    pokeTypeNum = pokeTypeNum + 1

inputPokeType = input("\nPlease enter the type number of the Pokemon: ")
element = driver.find_element_by_xpath("/html/body/div[4]/section[4]/div/span/b")
driver.execute_script("arguments[0].click();", element)
driver.implicitly_wait(10)
element = driver.find_element_by_xpath("/html/body/div[4]/section[3]/div[3]/div/div[1]/div[2]/ul/li[" + inputPokeType + "]/span[2]")
driver.execute_script("arguments[0].click();", element)
driver.implicitly_wait(10)
element = driver.find_element_by_xpath('//*[@id="search"]')
driver.execute_script("arguments[0].click();", element)
driver.implicitly_wait(10)
element = driver.find_element_by_xpath('//*[@id="loadMore"]/span')
driver.execute_script("arguments[0].click();", element)
driver.implicitly_wait(10)

time.sleep(3)
# Get scroll height
last_height = driver.execute_script("return document.body.scrollHeight")

while True:
    # Scroll down to bottom
    driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")

    # Wait to load page
    time.sleep(3)

    # Calculate new scroll height and compare with last scroll height
    new_height = driver.execute_script("return document.body.scrollHeight")
    if new_height == last_height:
        break
    last_height = new_height

source = driver.page_source
soup = BeautifulSoup(source, 'html.parser')
save_path = os.path.dirname(os.path.abspath(__file__))+"/images/"

for pokemonFound in soup.findAll("li", {"class": "animating"}):
    filename = pokemonFound.find("p",{"class": "id"}).text[1:5] + " - " + pokemonFound.find("h5").text
    print(filename)
    directory = os.path.join(save_path, filename + ".png")
    imagefile = open(directory, 'wb')
    imagefile.write(urllib.request.urlopen(pokemonFound.find("img")["src"]).read())
    imagefile.close()