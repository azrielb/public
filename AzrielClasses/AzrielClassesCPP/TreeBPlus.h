#pragma once
#include <list>
#include <stack>
#include <iostream>
// You can set (in your program) the "__BPlus_RANK" to be a bigger number, if you want a wider tree.
#ifndef __BPlus_RANK
#define __BPlus_RANK 3 // The upper index that MUST be not-null in every node is FLOOR((__BPlus_RANK-1)/2)
#endif

//This class can be declared by any types (T, K), with these restrictions:
//The type K MUST have a copy constructor and these seven operators: ==, !=, <=, <, >, >=, =.
//If you want use the "print" function then the type T must have the friend function "operator <<".
//If you want use the "add" function without the "key" parameter then the type T must have a member-function "GetKey()" that returns a K key.
template<class T, class K>
class treeBPlus
{
private:
	//Copy constructor can cause many errors, therefore it is prevented.
	//You can send to functions a pointer to the tree, or send it by reference.
	//Compiling error was written here on purpose to prevent using the copy constructor.
	treeBPlus(const treeBPlus<T, K> & oldTree) { 
		int i = "";
	}
protected:
	K index[__BPlus_RANK - 1];
	T * data;
	treeBPlus<T, K> * child[__BPlus_RANK];
	treeBPlus<T, K> * father;

	//protected constructors
	treeBPlus(treeBPlus<T, K> * myFather) : father(myFather), data(NULL) {
		for (int i = __BPlus_RANK - 1; i >= 0; --i)
			child[i] = NULL;
	}
	treeBPlus(treeBPlus<T, K> * myFather, T * theData, const K & index_0) 
		: father(myFather), data(theData) {
			index[0] = index_0;
			for (int i = __BPlus_RANK - 1; i >= 0; --i)
				child[i] = NULL;
	}

	//Find for a "key", and return whether it exists in the tree. the 2nd-parameter will contain the last node that was checked in the search.
	virtual bool find(const K & key, treeBPlus<T,K> * & lastNode) const
	{
		lastNode = (treeBPlus<T,K> *)this; // Convert from 'const treeBPlus<T,K> *const ' to 'treeBPlus<T,K> *'
		while (lastNode->child[0] != NULL) { // The node has a child if and only if it is not a leaf
			int i = 0;
			while (i < __BPlus_RANK - 1 && lastNode->child[i + 1] != NULL && key >= lastNode->index[i]) {
				++i;
			}
			lastNode = lastNode->child[i];
		}
		return (lastNode->data != NULL && *lastNode == key);
	}
	//Scan the children 1 time and swap pairs that are not ordered correctly.
	//The parameter "dir" (=direction) can be true for scan from left to right, or false for scan from right to left.
	//"true" is good while item is removed. "false" is good while item is added.
	virtual void sort(bool dir = true) {
		bool swapped = false;
		if (dir) {
			for (int i = 1; i < __BPlus_RANK; ++i) 
				if (checkAndSwap(i)) swapped = true;
				else if (swapped) return;
		} else {
			for (int i = __BPlus_RANK - 1; i > 0; --i)
				if (checkAndSwap(i)) swapped = true;
				else if (swapped) return;
		}
	}
	//check whether child[i] and child[i - 1] have to be swapped. if yes - swap them and return true, else return false.
	//Note: Incorrect parampeter can cause a runtime error!
	virtual bool checkAndSwap(int i){
		if (child[i] == NULL) {
			return false;
		} else if (child[i - 1] == NULL) {
			swap<treeBPlus<T, K> *>(child[i], child[i - 1]);
			if (i > 1)
				index[i - 2] = index[i - 1];
			return true;
		} else if (*(child[i]) < *(child[i - 1])) {
			swap<treeBPlus<T, K> *>(child[i], child[i - 1]);
			if (i > 1)
				swap<K>(index[i - 2], index[i - 1]);
			else {
				index[0] = child[1]->GetKey();
			}
			return true;
		} else {
			return false;
		}
	}
	/*
	* move children from other tree to "this".
	*
	* parameters:
	* src - the source-tree.
	* srcStart & dstCurrent - the places from which begin the transfer.
	* count - how much children to move.
	*
	* return the destination-index of the first child that was NOT moved (the 1st NULL, out of the count etc.).
	*
	* Note: calling this function on a leaves may cause unexpected errors!
	*/
	virtual int moveChildren(treeBPlus<T, K> * src, unsigned srcStart = 0, unsigned dstCurrent = 0, int count = __BPlus_RANK) {
		if (count + dstCurrent > __BPlus_RANK) count = __BPlus_RANK - dstCurrent;
		if (count + srcStart > __BPlus_RANK) count = __BPlus_RANK - srcStart;
		if (count <= 0) return dstCurrent;
		unsigned srcCurrent = srcStart;
		if (dstCurrent == 0 && src->child[srcCurrent] != NULL) {
			(child[0] = src->child[srcCurrent])->father = this;
			src->child[srcCurrent] = NULL;
			dstCurrent = 1;
			++srcCurrent;
			--count;
		} else if (srcCurrent == 0 && src->child[0] != NULL) {
			index[dstCurrent - 1] = src->child[0]->GetKey();
			(child[dstCurrent] = src->child[0])->father = this;
			src->child[0] = NULL;
			++dstCurrent;
			srcCurrent = 1;
			--count;
		}
		for (;count && src->child[srcCurrent] != NULL; --count) {
			index[dstCurrent - 1] = src->index[srcCurrent - 1];
			(child[dstCurrent] = src->child[srcCurrent])->father = this;
			src->child[srcCurrent] = NULL;
			++srcCurrent;
			++dstCurrent;
		}
		if (src->child[srcCurrent] != NULL) {
			if (srcStart == 0) {
				swap<treeBPlus<T, K> *>(src->child[srcStart],src->child[srcCurrent]);
				++srcCurrent;
			} else {
				--srcStart;
			}
			for (; srcCurrent < __BPlus_RANK && src->child[srcCurrent] != NULL; ++srcCurrent) {
				src->index[srcStart] = src->index[srcCurrent - 1];
				swap<treeBPlus<T, K> *>(src->child[++srcStart],src->child[srcCurrent]);
			}
		}
		int empty = dstCurrent;
		while (count--)
			child[dstCurrent++] = NULL;
		return empty;
	}
	//This function should be called before trying to get the "father"
	//If the tree has a father - return true. Else - test create father and return whether the father was created successfully. 
	static bool makeFather(treeBPlus<T, K> * & tree) {
		if (tree->father != NULL) return true;
		treeBPlus<T, K> * newChild;
		if (tree->data == NULL) {
			newChild = new treeBPlus<T, K>(tree);
			if (newChild == NULL) return false;
			newChild->moveChildren(tree);
		} else {
			newChild = new treeBPlus<T, K>(tree, tree->data, tree->index[0]);
			if (newChild == NULL) return false;
			tree->data = NULL;
		}
		tree = tree->child[0] = newChild;
		return true;
	}
public:
	//A single public constructor. 
	//The class has 2 protected constructors.
	//Copy constructor can cause many errors, therefore it is prevented.
	treeBPlus() : father(NULL), data(NULL) {
		for (int i = __BPlus_RANK - 1; i >= 0; --i)
			child[i] = NULL;
	}

	//return the key
	virtual K GetKey() const {
		return child[0] == NULL ? index[0] : child[0]->GetKey();
	}
	//Find an object and return its address or NULL if it does not exists in the tree.
	virtual const T * find(const K & key) {
		treeBPlus<T,K> * lastNode;
		return find(key, lastNode) ? lastNode->data : NULL;
	}

	//Add an object to the tree.
	//This function gets only value, and takes the key from the function "GetKey()" that must be declared in the type T.
	//Return whether the object was inserted successfully
	virtual bool add(const T &value) {
		return add(value, value.GetKey());
	}
	//Add an object to the tree.
	//This function have to get value+key. For example: add (person, name)
	//Return if the object was inserted successfully
	virtual bool add(const T &value, K key) {
		T * theValue = new T(value);
		if (theValue == NULL) return false;
		treeBPlus<T, K> * lastNode;
		if (find(key, lastNode)) return false;
		if (lastNode->child[0] == NULL && lastNode->data == NULL) {
			lastNode->data = theValue;
			lastNode->index[0] = key;
			return true;
		}
		if (!makeFather(lastNode)) return false;
		lastNode = lastNode->father;
		treeBPlus<T, K> * newNode = new treeBPlus<T, K>(lastNode, theValue, key);
		if (newNode == NULL) return false;
		while (lastNode->child[__BPlus_RANK - 1] != NULL) {
			if (!makeFather(lastNode)) return false;
			if (lastNode->index[__BPlus_RANK - 2] > key) {
				swap<treeBPlus<T, K> *>(lastNode->child[__BPlus_RANK - 1], newNode);
				swap<K>(lastNode->index[__BPlus_RANK - 2], key);
				lastNode->sort(false);
			}
			treeBPlus<T, K> * brother = new treeBPlus<T, K>(lastNode->father);
			if (brother == NULL) return false;
			int j = brother->moveChildren(lastNode, (__BPlus_RANK + 1) / 2, 0);
			newNode->father = brother;
			brother->index[j - 1] = key;
			brother->child[j] = newNode;
			newNode = brother;
			key = brother->GetKey();
			lastNode = lastNode->father;
		}
		newNode->father = lastNode;
		lastNode->child[__BPlus_RANK - 1] = newNode;
		lastNode->index[__BPlus_RANK - 2] = key;
		lastNode->sort(false);
		return true;
	}
	//Remove an object from the tree, and Return if the object was removed successfully
	virtual bool remove(const K & key) {
		treeBPlus<T, K> * lastNode = this;
		stack<int> indexes = stack<int>();
		int i;

		while (lastNode->child[0] != NULL) { // The node has a child if and only if it is not a leaf
			i = 0;
			while (i < __BPlus_RANK - 1 && lastNode->child[i + 1] != NULL && key >= lastNode->index[i]) 
				++i;
			indexes.push(i);
			lastNode = lastNode->child[i];
		}
		if (lastNode->data == NULL || *lastNode != key) return false; // The key was not found in the tree
		if (lastNode->father == NULL) { // Else - the destructor will do it.
			delete lastNode->data;
			lastNode->data = NULL;
			return true;
		}
		lastNode = lastNode->father;
		delete lastNode->child[i];
		lastNode->child[i] = NULL;
		lastNode->sort(true);
		while (lastNode->child[(__BPlus_RANK - 1) / 2] == NULL && lastNode->father != NULL) {
			indexes.pop();
			i = indexes.top();
			if (i < __BPlus_RANK - 1 && lastNode->father->child[i + 1] != NULL) {
				treeBPlus<T, K> * brother = lastNode->father->child[++i];
				if (brother->child[(__BPlus_RANK + 1) / 2] != NULL) {
					lastNode->moveChildren(brother, 0, (__BPlus_RANK - 1) / 2, 1);
					return true;
				} else {
					lastNode->moveChildren(brother, 0, (__BPlus_RANK - 1) / 2);
				}
			} else {
				treeBPlus<T, K> * brother = lastNode->father->child[i - 1];
				if (brother->child[(__BPlus_RANK + 1) / 2] != NULL) {
					int a = (__BPlus_RANK + 1) / 2 + 1;
					while (a < __BPlus_RANK && brother->child[a] != NULL) ++a;
					lastNode->moveChildren(brother, a - 1, (__BPlus_RANK - 1) / 2, 1);
					lastNode->sort(false);
					return true;
				} else {
					brother->moveChildren(lastNode, 0, (__BPlus_RANK + 1) / 2);
				}
			}
			lastNode = lastNode->father;
			delete lastNode->child[i];
			lastNode->child[i] = NULL;
			lastNode->sort(true);
		}
		// If the root has only one child and that child has at least one child - we have to delete the root
		if (child[1] == NULL && child[0] != NULL && child[0]->child[0] != NULL) {
			lastNode = child[0];
			moveChildren(lastNode);
			delete lastNode;
		}
		return true;
	}

	//Print all the leaves in the tree, separated by "glue", sorted by the keys.
	//Note: the type T must have the friend function "operator <<"
	virtual void print(char glue[] = "\n") {
		if (child[0] == NULL) {
			if (data != NULL) 
				cout << *data << glue;
		} else if (child[0]->child[0] == NULL) { 
			for (int i = 0; i < __BPlus_RANK && child[i] != NULL; ++i)
				cout << *(child[i]->data) << glue;
		} else {
			for (int i = 0; i < __BPlus_RANK && child[i] != NULL; ++i)
				child[i]->print(glue);
		}
	}

	//comparing functions - the node can be compared with other node or with a key
	virtual bool operator== (const K & key) const {
		return GetKey() == key;
	}
	virtual bool operator!= (const K & key) const {
		return GetKey() != key;
	}
	virtual bool operator<(const K & key) const {
		return GetKey() < key;
	}
	virtual bool operator>(const K & key) const {
		return GetKey() > key;
	}
	virtual bool operator<=(const K & key) const {
		return GetKey() <= key;
	}
	virtual bool operator>=(const K & key) const {
		return GetKey() >= key;
	}
	virtual bool operator== (const treeBPlus<T, K> & other) const {
		return GetKey() == other.GetKey();
	}
	virtual bool operator!= (const treeBPlus<T, K> & other) const {
		return GetKey() != other.GetKey();
	}
	virtual bool operator<(const treeBPlus<T, K> & other) const {
		return GetKey() < other.GetKey();
	}
	virtual bool operator>(const treeBPlus<T, K> & other) const {
		return GetKey() > other.GetKey();
	}
	virtual bool operator<=(const treeBPlus<T, K> & other) const {
		return GetKey() <= other.GetKey();
	}
	virtual bool operator>=(const treeBPlus<T, K> & other) const {
		return GetKey() >= other.GetKey();
	}

	//destructor
	virtual ~treeBPlus(void)
	{
		for (int i = __BPlus_RANK - 1; i >= 0; --i) {
			if (child[i] != NULL)
				delete child[i];
		}
		if (data != NULL)
			delete data;
	}
};
